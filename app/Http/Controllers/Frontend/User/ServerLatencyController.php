<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

/**
 * Class ServerLatencyController.
 */
class ServerLatencyController extends Controller
{

    public function index($id)
    {
        $server_all = DB::table('monitors')->find($id);
        $from = '';
        $to = '';
        $end = date('Y-m-d H:i:s');
        $start = date('Y-m-d H:i:s', strtotime('-1 day'));
        $serverlatency = DB::table('server_latency')
            ->where('server_id', '=', $id)
            ->where('created_at','<=', $end)
            ->where('created_at','>=', $start)
            ->orderBy('created_at', 'DESC')
            ->get();

        $latency = [];
        $date = [];
        foreach ($serverlatency as $sl){
            $latency[] = $sl->latency;
            $date[] = $sl->created_at;
        }
        return view('frontend.user.serverlatency', compact('server_all', 'date','latency', 'serverlatency', 'from', 'to'));
    }

    public function filter(Request $request)
    {

        $from = $request->from_date;
        $to = $request->to_date;
        $id = $request->id;

        $server_all = DB::table('monitors')->find($id);
        $serverlatency = DB::table('server_latency')
            ->where('server_id', '=', $id)
            ->whereDate('created_at','<=', $to)
            ->whereDate('created_at','>=', $from)
            ->get();
        $latency = [];
        $date = [];
        foreach ($serverlatency as $sl){
            $latency[] = $sl->latency;
            $date[] = $sl->created_at;
        }
        return view('frontend.user.serverlatency', compact('server_all', 'date','latency', 'serverlatency' , 'from', 'to'));
    }


}