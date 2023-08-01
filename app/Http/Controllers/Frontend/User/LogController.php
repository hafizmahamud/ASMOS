<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use \Datetime;
/**
 * Class DashboardController.
 */
class LogController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {   
        $user = Auth::user();
        $start = '';
        $end = '';
        if($user->role == 'admin'){
            $log_all = DB::table('monitors')
                ->join('log_server', 'monitors.id', '=', 'log_server.server_id')
                ->distinct('log_server.created_at')
                ->orderBy('log_server.created_at', 'DESC')
                ->get();
            $server_all = DB::table('monitors')->get();
        }else{
            $log_all = DB::table('server_user')->where('user_id', $user->id)
                ->join('log_server', 'server_user.server_id', '=', 'log_server.server_id')
                ->distinct('log_server.created_at')
                ->orderBy('log_server.created_at', 'DESC')
                ->leftJoin('monitors', 'server_user.server_id', '=', 'monitors.id')
                ->get();

            $server_all = DB::table('server_user')->where('user_id', $user->id)
                ->leftJoin('monitors', 'server_user.server_id', '=', 'monitors.id')
                ->get();
        }
        return view('frontend.user.log', compact('log_all','server_all', 'start','end'));
    }

    public function filter(Request $request)
    {   
        $user = Auth::user();
        $start = $request->from_date;
        $end = $request->to_date;

        if($user->role == 'admin'){
            $log_all = DB::table('monitors')
                ->join('log_server', 'monitors.id', '=', 'log_server.server_id')
                ->distinct('log_server.created_at')
                ->whereDate('log_server.created_at','<=', $end)
                ->whereDate('log_server.created_at','>=', $start)
                ->orderBy('log_server.created_at', 'DESC')
                ->get();
            $server_all = DB::table('monitors')->get();
        }else{
            $log_all = DB::table('server_user')->where('user_id', $user->id)
                ->join('log_server', 'server_user.server_id', '=', 'log_server.server_id')
                ->distinct('log_server.created_at')
                ->whereDate('log_server.created_at','<=', $end)
                ->whereDate('log_server.created_at','>=', $start)
                ->orderBy('log_server.created_at', 'DESC')
                ->leftJoin('monitors', 'server_user.server_id', '=', 'monitors.id')
                ->get();

            $server_all = DB::table('server_user')->where('user_id', $user->id)
                ->leftJoin('monitors', 'server_user.server_id', '=', 'monitors.id')
                ->get();
        }
        return view('frontend.user.log', compact('log_all','server_all', 'start','end'));
    }

    public function destroy()
    {
        $server_all = DB::table('log_server')->delete();
        return redirect()->route('frontend.user.log')->withFlashSuccess(__('strings.frontend.user.log_deleted'));
    }
}
