<?php

namespace App\Traits;

use DB;

trait KpiTrait
{
    public function kpiMetrics($kpi)
    {
        return DB::table('metrics')
            ->where('kpi_id', $kpi)
            ->get();
    }

    public function metricDataInputs($metric)
    {
        return DB::table('data_input')
            ->where('metric_id', $metric)
            ->get();
    }

    public function metricResult($metric, $month, $year)
    {
        return DB::table('kpi_datainput_result')
            ->join('data_input', 'data_input.input_id', 'kpi_datainput_result.data_input_id')
            ->where('data_input.metric_id', $metric)
            ->where('kpi_datainput_result.user_id', null)
            ->where('month', $month)->where('year', $year)
            ->sum('answer');
    }

    public function dataInputResult($data_input, $month, $year)
    {
        return DB::table('kpi_datainput_result')
            ->where('data_input_id', $data_input)
            ->where('user_id', null)
            ->where('month', $month)->where('year', $year)
            ->first();
    }
}
