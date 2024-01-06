<?php

namespace App\Charts;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class trafficChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($username, $day): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $end = Carbon::now();
        $start = $end->copy()->subDays($day);
        $data = DB::table('traffic_data')->whereBetween('timestamp', [$start->startOfDay(), $end->endOfDay()])->where('username', $username)->orderByDesc('id')->get();

        $labels = [];
        $rx = [];
        $tx = [];

        foreach ($data as $i) {
            array_push($labels, $i->timestamp);
            array_push($rx, round(intval($i->rx) * 0.000001, 3));
            array_push($tx, round(intval($i->tx) * 0.000001, 3));
        }

        return $this->chart->lineChart()
            ->setTitle($data[0]->username)
            ->setSubtitle('Rx / Tx Client Statistics')
            ->addData('Rx', $rx)
            ->addData('Tx', $tx)
            ->setXAxis($labels);
    }
}
