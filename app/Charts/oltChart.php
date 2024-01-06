<?php

namespace App\Charts;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class oltChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($mac, $day): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $end = Carbon::now();
        $start = $end->copy()->subDays($day);
        $data = DB::table('data')->whereBetween('timestamp', [$start->startOfDay(), $end->endOfDay()])->where('mac', $mac)->orderByDesc('id')->get();

        $labels = [];
        $values = [];

        foreach ($data as $i) {
            array_push($labels, $i->timestamp);
            array_push($values, $i->power);
        }

        return $this->chart->lineChart()
            ->setTitle($data[0]->name)
            ->setSubtitle('Receive Power Optical Statistics')
            ->addData('Dbm', $values)
            ->setXAxis($labels);
    }
}
