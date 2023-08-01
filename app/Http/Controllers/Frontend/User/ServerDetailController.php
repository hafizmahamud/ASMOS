<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use DB;
/**
 * Class DashboardController.
 */
class ServerDetailController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        $server_all = DB::table('monitors')->find($id);
        $end = date('Y-m-d H:i:s');
        $start = date('Y-m-d H:i:s', strtotime('-1 day'));
        $serverlatency = DB::table('server_latency')
            ->where('server_id', '=', $id)
            ->where('created_at','<=', $end)
            ->where('created_at','>=', $start)
            ->orderBy('created_at', 'DESC')
            ->get();
        
        $log_down = DB::table('log_server')
            ->where('server_id', $id)
            ->where('services', 'Network')
            ->where('status', 'down')
            ->distinct('created_at')
            ->latest()->first();

        $log_up = DB::table('log_server')
            ->where('server_id', $id)
            ->where('services', 'Network')
            ->where('status', 'up')
            ->distinct('created_at')
            ->latest()->first();

        $log_error = DB::table('log_server')
            ->where('server_id', $id)
            ->where('status', 'down')
            ->distinct('created_at')
            ->latest()->first();
        
        $log_positive = DB::table('log_server')
            ->where('server_id', $id)
            ->where('status', 'up')
            ->distinct('created_at')
            ->latest()->first();
 
        $latency = [];
        $date = [];
        foreach ($serverlatency as $sl){
               $latency[] = $sl->latency;
               $date[] = $sl->created_at;
        } 
        
        return view('frontend.user.serverdetail' , compact('server_all','latency','date', 'log_down', 'log_up','log_error','log_positive'));
    }
}
