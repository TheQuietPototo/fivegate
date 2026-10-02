<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatAssistantController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|string',
            'messages.*.content' => 'required|string',
            'model' => 'nullable|string',
            'stream' => 'nullable|boolean',
            'session_id' => 'nullable|string',
        ]);

        $apiKey = config('services.groq.api_key', env('GROQ_API_KEY'));
        $endpoint = config('services.groq.endpoint', env('GROQ_ENDPOINT', 'https://api.groq.com/openai/v1/chat/completions'));
        $model = $validated['model'] ?? config('services.groq.default_model', env('GROQ_DEFAULT_MODEL', 'openai/gpt-oss-20b'));
        $stream = $validated['stream'] ?? true;
        $sessionId = $validated['session_id'] ?? null;

        // Save last user message if present
        $lastMessage = collect($validated['messages'])->last();
        if ($lastMessage && $lastMessage['role'] === 'user') {
            ChatMessage::create([
                'session_id' => $sessionId,
                'role' => 'user',
                'content' => $lastMessage['content'],
                'model' => $model,
            ]);
        }

        if (!$apiKey) {
            return response()->json([
                'error' => [
                    'message' => 'Groq API key is not configured on the server. Please add GROQ_API_KEY to your .env file.',
                    'type' => 'configuration_error',
                ],
            ], 500);
        }

        $payload = [
            'model' => $model,
            'messages' => $validated['messages'],
            'temperature' => 0.4,
            'stream' => (bool) $stream,
        ];

        if ($stream) {
            return new StreamedResponse(function () use ($endpoint, $apiKey, $payload, $sessionId, $model) {
                $accumulated = '';

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->withOptions([
                    'stream' => true,
                ])->post($endpoint, $payload);

                $body = $response->toPsrResponse()->getBody();

                while (!$body->eof()) {
                    $chunk = $body->read(1024);
                    echo $chunk;
                    flush();

                    // Parse SSE chunks to aggregate content for database logging
                    foreach (explode("\n", $chunk) as $line) {
                        $line = trim($line);
                        if (str_starts_with($line, 'data: ') && $line !== 'data: [DONE]') {
                            $jsonStr = substr($line, 6);
                            $data = json_decode($jsonStr, true);
                            $piece = $data['choices'][0]['delta']['content'] ?? '';
                            $accumulated .= $piece;
                        }
                    }
                }

                if (!empty($accumulated)) {
                    ChatMessage::create([
                        'session_id' => $sessionId,
                        'role' => 'assistant',
                        'content' => $accumulated,
                        'model' => $model,
                    ]);
                }
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'X-Accel-Buffering' => 'no',
            ]);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post($endpoint, $payload);

        if ($response->failed()) {
            return response()->json($response->json() ?? ['error' => $response->body()], $response->status());
        }

        $responseData = $response->json();
        $assistantContent = $responseData['choices'][0]['message']['content'] ?? '';

        if (!empty($assistantContent)) {
            ChatMessage::create([
                'session_id' => $sessionId,
                'role' => 'assistant',
                'content' => $assistantContent,
                'model' => $model,
            ]);
        }

        return response()->json($responseData);
    }

    public function history(Request $request): JsonResponse
    {
        $sessionId = $request->query('session_id');

        $query = ChatMessage::latest();
        if ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->take(50)->get()->reverse()->values(),
        ]);
    }
}
