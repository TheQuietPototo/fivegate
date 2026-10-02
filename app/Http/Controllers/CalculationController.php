<?php

namespace App\Http\Controllers;

use App\Models\CalculationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index(): JsonResponse
    {
        $logs = CalculationLog::latest()->take(20)->get();

        return response()->json([
            'status' => 'success',
            'data' => $logs,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'flow_before' => 'required|numeric|min:0',
            'cod_before' => 'required|numeric|min:0',
            'err_before' => 'nullable|numeric|min:0',
            'flow_after' => 'required|numeric|min:0',
            'cod_after' => 'required|numeric|min:0',
            'err_after' => 'nullable|numeric|min:0',
            'kwh_extra' => 'nullable|numeric|min:0',
            'kwh_saved' => 'nullable|numeric|min:0',
            'ef' => 'nullable|numeric|min:0',
        ]);

        $flowB = (float) $validated['flow_before'];
        $codB = (float) $validated['cod_before'];
        $errB = (float) ($validated['err_before'] ?? 10);

        $flowA = (float) $validated['flow_after'];
        $codA = (float) $validated['cod_after'];
        $errA = (float) ($validated['err_after'] ?? 10);

        $kwhExtra = (float) ($validated['kwh_extra'] ?? 0);
        $kwhSaved = (float) ($validated['kwh_saved'] ?? 0);
        $ef = (float) ($validated['ef'] ?? 0.85);

        $loadB = ($flowB * $codB) / 1000.0;
        $loadA = ($flowA * $codA) / 1000.0;
        $loadChange = $loadA - $loadB;

        $uB = $loadB * ($errB / 100.0);
        $uA = $loadA * ($errA / 100.0);
        $uncComb = 2.0 * sqrt(($uB * $uB) + ($uA * $uA));

        $co2Net = ($kwhExtra - $kwhSaved) * $ef;

        if ($loadChange < 0 && abs($loadChange) > $uncComb) {
            $verdict = 'clear_reduction';
        } elseif ($loadChange < 0) {
            $verdict = 'within_uncertainty';
        } else {
            $verdict = 'no_reduction';
        }

        $log = CalculationLog::create([
            'flow_before' => $flowB,
            'cod_before' => $codB,
            'load_before' => $loadB,
            'err_before' => $errB,
            'flow_after' => $flowA,
            'cod_after' => $codA,
            'load_after' => $loadA,
            'err_after' => $errA,
            'load_change' => $loadChange,
            'unc_combined' => $uncComb,
            'kwh_extra' => $kwhExtra,
            'kwh_saved' => $kwhSaved,
            'co2_net' => $co2Net,
            'verdict' => $verdict,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $log->id,
                'load_before' => round($loadB, 3),
                'load_after' => round($loadA, 3),
                'load_change' => round($loadChange, 3),
                'unc_combined' => round($uncComb, 3),
                'kwh_net' => round($kwhExtra - $kwhSaved, 3),
                'co2_net' => round($co2Net, 3),
                'verdict' => $verdict,
            ],
        ], 201);
    }
}
