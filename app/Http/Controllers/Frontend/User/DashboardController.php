<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon\Carbon;
use \Datetime;
/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        if($user->role == 'admin'){
            $server_all = DB::table('monitors')
                ->orderBy('status', 'ASC')
                ->orderBy('database_status', 'ASC')
                ->orderBy('uptime_status', 'ASC')
                ->orderBy('api', 'ASC')
                ->get();
        }else{
            $server_all = DB::table('server_user')->where('user_id', $user->id)
                ->leftJoin('monitors', 'server_user.server_id', '=', 'monitors.id')
                ->orderBy('status', 'ASC')
                ->orderBy('database_status', 'ASC')
                ->orderBy('uptime_status', 'ASC')
                ->orderBy('api', 'ASC')
                ->get();
        }
        for ($i=0; $i<count($server_all); $i++) {
            $uptime = $server_all[$i]->uptime_status;
            $status = $server_all[$i]->status;
            $api = $server_all[$i]->api;
            $database = $server_all[$i]->database_status;
            $ssl = $server_all[$i]->certificate_status;

            if ($ssl == 'invalid'){
                $array = array( $uptime, $api, $database, $ssl, $status);
                $counts = array_count_values($array);
                if (in_array("up", $array)){
                    $issue = 5- $counts['up'];
                } else {
                    $issue = 5;
                }
            } else {
                $ssl_date = $server_all[$i]->certificate_expiration_date;
                $today = new Carbon();
                $carbon = new Carbon($ssl_date);
                $months = $today->floatDiffInMonths($carbon);

                if ($months >= 3.1){
                    $ssl_status = 'up';
                } else {
                    $ssl_status = 'down';
                }

                $array = array( $uptime, $api, $database, $ssl_status, $status);
                $counts = array_count_values($array);
                $issue = 5- $counts['up'];
            }

            DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                'issue'=>$issue,
                ]);

        }

        return view('frontend.user.dashboard', compact('server_all'));
    }

}
