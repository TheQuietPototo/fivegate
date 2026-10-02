<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationLog extends Model
{
    protected $fillable = [
        'flow_before',
        'cod_before',
        'load_before',
        'err_before',
        'flow_after',
        'cod_after',
        'load_after',
        'err_after',
        'load_change',
        'unc_combined',
        'kwh_extra',
        'kwh_saved',
        'co2_net',
        'verdict',
    ];

    protected $casts = [
        'flow_before' => 'float',
        'cod_before' => 'float',
        'load_before' => 'float',
        'err_before' => 'float',
        'flow_after' => 'float',
        'cod_after' => 'float',
        'load_after' => 'float',
        'err_after' => 'float',
        'load_change' => 'float',
        'unc_combined' => 'float',
        'kwh_extra' => 'float',
        'kwh_saved' => 'float',
        'co2_net' => 'float',
    ];
}
