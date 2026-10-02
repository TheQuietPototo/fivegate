<?php

namespace App\Http\Controllers;

use App\Models\IdeaCheck;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IdeaCheckController extends Controller
{
    public function index(): JsonResponse
    {
        $checks = IdeaCheck::latest()->take(20)->get();

        return response()->json([
            'status' => 'success',
            'data' => $checks,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'role' => 'nullable|string|max:255',
            'path' => 'nullable|string|max:255',
            'idea' => 'required|string',
            'answers' => 'nullable|array',
            'result_status' => 'required|string|max:50',
            'stopped_gate' => 'nullable|integer|min:1|max:5',
        ]);

        $check = IdeaCheck::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Hasil pengecekan berhasil disimpan.',
            'data' => $check,
        ], 201);
    }
}
