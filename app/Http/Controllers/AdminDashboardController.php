<?php

namespace App\Http\Controllers;

use App\Models\CalculationLog;
use App\Models\ChatMessage;
use App\Models\IdeaCheck;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDashboardController extends Controller
{
    /**
     * View Admin Dashboard with stats and table data.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'checks');
        $search = $request->query('search', '');

        // Summary Stats
        $stats = [
            'total_checks' => IdeaCheck::count(),
            'passed_checks' => IdeaCheck::where('result_status', 'pass')->count(),
            'total_calculations' => CalculationLog::count(),
            'total_chats' => ChatMessage::where('role', 'user')->count(),
        ];

        // Data queries
        $ideaChecksQuery = IdeaCheck::latest();
        if ($search && $tab === 'checks') {
            $ideaChecksQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('idea', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }
        $ideaChecks = $ideaChecksQuery->paginate(15, ['*'], 'checks_page')->withQueryString();

        $calcLogsQuery = CalculationLog::latest();
        if ($search && $tab === 'calculations') {
            $calcLogsQuery->where('verdict', 'like', "%{$search}%");
        }
        $calcLogs = $calcLogsQuery->paginate(15, ['*'], 'calc_page')->withQueryString();

        $chatMessagesQuery = ChatMessage::latest();
        if ($search && $tab === 'chats') {
            $chatMessagesQuery->where('content', 'like', "%{$search}%")
                ->orWhere('session_id', 'like', "%{$search}%");
        }
        $chatMessages = $chatMessagesQuery->paginate(15, ['*'], 'chat_page')->withQueryString();

        return view('admin.dashboard', compact('stats', 'ideaChecks', 'calcLogs', 'chatMessages', 'tab', 'search'));
    }

    /**
     * Export dataset as CSV or JSON.
     */
    public function export(Request $request, string $type)
    {
        $format = strtolower($request->query('format', 'csv'));
        $filename = "limagerbang_{$type}_" . date('Y-m-d_His');

        if ($format === 'json') {
            return $this->exportJson($type, $filename);
        }

        return $this->exportCsv($type, $filename);
    }

    /**
     * Delete a single record.
     */
    public function destroy(Request $request, string $type, int $id)
    {
        switch ($type) {
            case 'checks':
                IdeaCheck::findOrFail($id)->delete();
                break;
            case 'calculations':
                CalculationLog::findOrFail($id)->delete();
                break;
            case 'chats':
                ChatMessage::findOrFail($id)->delete();
                break;
            default:
                return back()->with('error', 'Jenis data tidak valid.');
        }

        return back()->with('success', 'Data berhasil dihapus.');
    }

    private function exportCsv(string $type, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        return response()->stream(function () use ($type) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            if ($type === 'checks') {
                fputcsv($file, ['ID', 'Nama', 'Email', 'Peran', 'Jenis Ide', 'Deskripsi Ide', 'Status Hasil', 'Gerbang Terhenti', 'Jawaban (JSON)', 'Tanggal Dibuat']);
                IdeaCheck::chunk(100, function ($rows) use ($file) {
                    foreach ($rows as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->name,
                            $row->email,
                            $row->role,
                            $row->path,
                            $row->idea,
                            $row->result_status,
                            $row->stopped_gate ?? '-',
                            json_encode($row->answers, JSON_UNESCAPED_UNICODE),
                            $row->created_at,
                        ]);
                    }
                });
            } elseif ($type === 'calculations') {
                fputcsv($file, ['ID', 'Debit Sebelum (m3/hr)', 'COD Sebelum (mg/L)', 'Beban Sebelum (kg/hr)', 'Err Sebelum (%)', 'Debit Sesudah (m3/hr)', 'COD Sesudah (mg/L)', 'Beban Sesudah (kg/hr)', 'Err Sesudah (%)', 'Perubahan Beban', 'Ketidakpastian', 'kWh Extra', 'kWh Saved', 'CO2 Net (kg)', 'Hasil Verdict', 'Tanggal Dibuat']);
                CalculationLog::chunk(100, function ($rows) use ($file) {
                    foreach ($rows as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->flow_before,
                            $row->cod_before,
                            $row->load_before,
                            $row->err_before,
                            $row->flow_after,
                            $row->cod_after,
                            $row->load_after,
                            $row->err_after,
                            $row->load_change,
                            $row->unc_combined,
                            $row->kwh_extra,
                            $row->kwh_saved,
                            $row->co2_net,
                            $row->verdict,
                            $row->created_at,
                        ]);
                    }
                });
            } elseif ($type === 'chats') {
                fputcsv($file, ['ID', 'Session ID', 'Role', 'Content', 'Model', 'Tanggal Dibuat']);
                ChatMessage::chunk(100, function ($rows) use ($file) {
                    foreach ($rows as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->session_id,
                            $row->role,
                            $row->content,
                            $row->model,
                            $row->created_at,
                        ]);
                    }
                });
            }

            fclose($file);
        }, 200, $headers);
    }

    private function exportJson(string $type, string $filename)
    {
        if ($type === 'checks') {
            $data = IdeaCheck::all();
        } elseif ($type === 'calculations') {
            $data = CalculationLog::all();
        } elseif ($type === 'chats') {
            $data = ChatMessage::all();
        } else {
            return back()->with('error', 'Jenis data tidak valid.');
        }

        return response()->json($data, 200, [
            'Content-Disposition' => "attachment; filename=\"{$filename}.json\"",
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
