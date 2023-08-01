<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Mail;
use Artisan;
use Carbon\Carbon;

/**
 * Class DashboardController.
 */
class ServerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        if($user->role == 'admin'){
            $server_all = DB::table('monitors')->get();
        }else{
            $server_all = DB::table('server_user')->where('user_id', $user->id)
                ->leftJoin('monitors', 'server_user.server_id', '=', 'monitors.id')
                ->get();
        }
        return view('frontend.user.server', compact('server_all'));
    }

    public function update(Request $request)
    {   
        $uptime = Artisan::call('monitor:check-uptime');
        $ssl = Artisan::call('monitor:check-certificate');
        
        $server_all = DB::table('monitors')->get();
        for ($i=0; $i<count($server_all); $i++) {
            $type = $server_all[$i]->type;
            $url = $server_all[$i]->url;
            $http = parse_url($url, PHP_URL_SCHEME);
            if ($http == 'http'){
                $http_port = 80;
            }else {
                $http_port = 443;
            }
            $ip = $server_all[$i]->ip;
            $headers = get_headers($url);
            $code = substr($headers[0], 9, 3);
            $label = $server_all[$i]->label;
            $timeout = $server_all[$i]->timeout;
            $time = date('Y-m-d H:i:s');
            preg_match('/\s(\d+)\s/', $headers[0], $matches);
            $api = $matches[0];

            if ($api <= 290){
                $api_status = 'up';
                $api_output = 'API server '.$label.'  is RUNNING: ip = '.$ip.', with response code = '.$code.', the request was successfully received, understood, and accepted.';

            }else if ($api >= 290 && $api <= 390){
                $api_status = 'redirection';
                $api_output = 'API server '.$label.' is REDIRECT: ip = '.$ip.', with response code = '.$code.', further action needs to be taken in order to complete the request.';

            }else {
                $api_status = 'down';
                $api_output = 'API server '.$label.'  is DOWN: ip = '.$ip.', with response code = '.$code.', the server failed to fulfil an apparently valid request.';
            }

            $api_before = $server_all[$i]->api;
            if ($api_status != $api_before){

                DB::table('log_server')
                    ->insert([
                        'status'=>$api_status,
                        'status_log'=>$api_output,
                        'services'=>'API',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);

                DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                    'api'=>$api_status,
                    'api_code'=>$api,
                    'api_output'=>$api_output,
                    ]);
            }

            $uptime_before = $server_all[$i]->uptime_before_status;
            $uptime_after = $server_all[$i]->uptime_status;
            if ($uptime_after != $uptime_before){

                if ($uptime_after == 'up'){
                    $uptime_output = 'Server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', the response was successfully received.';
                }else {
                    $uptime_failure = $server_all[$i]->uptime_check_failure_reason;
                    $uptime_output = 'Server '.$label.'  is RUNNING: ip = '.$ip.', '.$uptime_failure;
                }

                DB::table('log_server')
                    ->insert([
                        'status'=>$uptime_after,
                        'status_log'=>$uptime_output,
                        'services'=>'Web Server',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);

                DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                    'uptime_before_status'=>$uptime_after,
                ]);
            }
        
            $ssl_after = $server_all[$i]->certificate_status;
            $ssl_before = $server_all[$i]->certificate_before_status;
            $ssl_date = $server_all[$i]->certificate_expiration_date;
            $carbon = new Carbon($ssl_date);
            $ssl_diff = $carbon->diffForHumans();

            if ($ssl_after != $ssl_before){

                if ($ssl_after == 'invalid'){
                    $ssl_status = 'Not Applicable';
                    $ssl_failure = $server_all[$i]->certificate_check_failure_reason;
                    $ssl_output = 'Server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', '.$ssl_failure;

                }else {
                    $ssl_status = 'Applicable';
                    $ssl_output = 'Server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', the expiration date is '.$ssl_diff;
                }
                DB::table('log_server')
                    ->insert([
                        'status'=>$ssl_status,
                        'status_log'=>$ssl_output,
                        'services'=>'SSL Cert',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);

                DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                    'certificate_before_status'=>$ssl_after,
                ]);
            }
            
            if ($type == 'Ping'){
                $timeout = $server_all[$i]->timeout;
                $milisecond = $timeout * 1000;
                $result = exec('ping '.gethostbyname($ip));
                $chunks = explode(' ', $result);
                $laten = $chunks[12];
                $ms = preg_replace("/[^0-9]/", "", $laten);
                $lost = $chunks[10];
                if ($lost != '0,' && $ms <= $milisecond){
                    $status = 'up';
                    $status_output = 'Server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', the response was successfully received.';
                    $latency = $ms;
                }else if ($lost != '0,' && $ms >= $milisecond){
                    $status = 'slow';
                    $status_output = 'Server '.$label.'  is SLOW: ip = '.$ip.', with port = '.$http_port.', it takes longer to response that the timeout = $milisecond ms given.';
                    $latency = $ms;
                }else {
                    $status = 'down';
                    $status_output = 'Server '.$label.'  is DOWN: ip = '.$ip.', with port = '.$http_port.', TIMEOUT ERROR: no response from server.';
                    $latency = 0;
                }
            }else {
                $timeout = $server_all[$i]->timeout;
                $milisecond = $timeout * 1000;
                $ch = curl_init($ip);
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($ch);
                $info = curl_getinfo($ch);
                $connect_time = $info['connect_time'];
                $late = $connect_time * 1000;
                $latency = ceil($late);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if($httpcode !== 0 && $latency <= $milisecond){
                    $status = 'up';
                    $status_output = 'Server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', the response was successfully received.';
                    $latency = $latency;
                } else if ($httpcode !== 0 && $latency >= $milisecond){
                    $status = 'slow';
                    $status_output = 'Server '.$label.'  is SLOW: ip = '.$ip.', with port = '.$http_port.', it takes longer to response that the timeout = $milisecond ms given.';
                    $latency = $latency;
                }else {
                    $status = 'down';
                    $status_output = 'Server '.$label.'  is DOWN: ip = '.$ip.', with port = '.$http_port.', TIMEOUT ERROR: no response from server.';
                    $latency = 0;
                }
            }
            
            $status_before = $server_all[$i]->status;
            if ($status != $status_before){

                DB::table('log_server')
                    ->insert([
                        'status'=>$status,
                        'status_log'=>$status_output,
                        'services'=>'Network',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);

                DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                    'status'=>$status,
                    'status_log'=>$status_output,
                    'pattern'=>$latency,
                ]);
            }

            DB::table('server_latency')
                ->insert([
                    'status'=>$status,
                    'latency'=>$latency,
                    'server_id'=>$server_all[$i]->id,
                    'created_at'=>$time,
                    'updated_at'=>$time,
                ]);
            
            $database = $server_all[$i]->database;
            $data_name = $server_all[$i]->database_name;
            $data_user = $server_all[$i]->database_username;
            $data_pass = $server_all[$i]->database_password;
            $data_port = $server_all[$i]->database_port;
            $ip = $server_all[$i]->ip;
            if ($database == 'Mysql'){
                $conn = @mysqli_connect($ip, $data_user, $data_pass);
                if (!$conn) {
                    $data_output = 'Database server '.$label.' is DOWN. ip = '.$ip.', with port = '.$data_port.', failed to connect. Please try again.';
                    $data_status = 'down';
                }else{
                    $data_output = 'Database server '.$label.' is RUNNING. ip = '.$ip.', with port = '.$data_port.', test connection database successful.' . mysqli_get_host_info($conn);
                    $data_status = 'up';
                }
            }else {
                $dbconn3 = @pg_connect("host=$ip port=$data_port dbname=$data_name user=$data_user password=$data_pass");
                if (!$dbconn3) {
                    $data_output = 'Database server '.$label.' is DOWN. ip = '.$ip.', with port = '.$data_port.', failed to connect. Please try again.';
                    $data_status = 'down';
                }else{
                    $data_output = 'Database server '.$label.' is RUNNING. ip = '.$ip.', with port = '.$data_port.', test connection database successful.';
                    $data_status = 'up';
                }
            }

            $data_before = $server_all[$i]->database_status;
            if ($data_status != $data_before){

                DB::table('log_server')
                    ->insert([
                        'status'=>$data_status,
                        'status_log'=>$data_output,
                        'services'=>'Database',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);

                DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                    'database_status'=>$data_status,
                    'database_output'=>$data_output,
                    'updated_at'=>$time,
                ]);
            }
        }
        return redirect()->route('frontend.user.dashboard')->withFlashSuccess(__('strings.frontend.user.server_updated'));

    }
}