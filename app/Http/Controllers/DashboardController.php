<?php

namespace App\Http\Controllers;

use App\Charts\oltChart;
use App\Charts\trafficChart;
use Carbon\Carbon;
use \RouterOS\Query;
use \RouterOS\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $client = new Client([
            'host' => env('ROUTER_HOST'),
            'user' => env('ROUTER_USER'),
            'pass' => env('ROUTER_PASSWORD'),
            'port' => intval(env('ROUTER_PORT'))
        ]);

        $clients = $client->query('/ppp/secret/print')->read();
        $actives = $client->query('/ppp/active/print', ['service', 'pppoe'])->read();
        $profiles = $client->query('/ppp/profile/print')->read();
        $resources = $client->query('/system/resource/print')->read()[0];

        $lives = DB::table('live')->get();

        $traffic_live = DB::table('traffic_data')->get();

        return view('dashboard', compact('clients', 'actives', 'profiles', 'resources', 'lives', 'traffic_live'));
    }

    public function add(Request $request)
    {
        $client = new Client([
            'host' => env('ROUTER_HOST'),
            'user' => env('ROUTER_USER'),
            'pass' => env('ROUTER_PASSWORD'),
            'port' => intval(env('ROUTER_PORT'))
        ]);

        $query = new Query('/ppp/secret/add');
        $query->equal('comment', $request->name);
        $query->equal('name', $request->username);
        $query->equal('password', $request->password);
        $query->equal('profile', $request->profile);
        $query->equal('service', 'pppoe');
        $client->query($query)->read();

        return back();
    }

    public function edit(Request $request)
    {
        $client = new Client([
            'host' => env('ROUTER_HOST'),
            'user' => env('ROUTER_USER'),
            'pass' => env('ROUTER_PASSWORD'),
            'port' => intval(env('ROUTER_PORT'))
        ]);

        $query = new Query('/ppp/secret/set');
        $query->equal('.id', $request->id);
        $query->equal('comment', $request->name);
        $query->equal('name', $request->username);
        $query->equal('password', $request->password);
        $query->equal('profile', $request->profile);
        $client->query($query)->read();

        return back();
    }

    public function remove(Request $request)
    {
        $client = new Client([
            'host' => env('ROUTER_HOST'),
            'user' => env('ROUTER_USER'),
            'pass' => env('ROUTER_PASSWORD'),
            'port' => intval(env('ROUTER_PORT'))
        ]);

        $query = new Query('/ppp/secret/remove');
        $query->equal('.id', $request->id);
        $client->query($query)->read();

        $active = $client->query('/ppp/active/print', ['name', $request->name])->read();
        if (count($active) > 0) {
            $query = new Query('/ppp/active/remove');
            $query->equal('.id', $active[0]['.id']);
            $client->query($query)->read();
        }

        return back();
    }

    public function login()
    {
        return view('login');
    }

    public function auth(Request $request)
    {
        if ($request->password === env('APP_PASSWORD')) {
            session(['login' => true]);
        } else {
            session()->flush();
        }
        return redirect('/');
    }

    public function graph($mac, $day, oltChart $chart)
    {
        $end = Carbon::now();
        $start = $end->copy()->subDays($day);
        $data = DB::table('data')->whereBetween('timestamp', [$start->startOfDay(), $end->endOfDay()])->where('mac', $mac)->orderByDesc('id')->get();

        $chart = $chart->build($mac, $day);

        return view('graph', compact('data', 'mac', 'day', 'chart'));
    }

    public function general_graph($username, $day, trafficChart $chart)
    {
        $end = Carbon::now();
        $start = $end->copy()->subDays($day);
        $data = DB::table('traffic_data')->whereBetween('timestamp', [$start->startOfDay(), $end->endOfDay()])->where('username', $username)->orderByDesc('id')->get();

        $chart = $chart->build($username, $day);

        return view('general_graph', compact('data', 'username', 'day', 'chart'));
    }
}
