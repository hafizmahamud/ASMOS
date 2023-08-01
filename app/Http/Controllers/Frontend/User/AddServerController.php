<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use DB;
use Artisan;
use Carbon\Carbon;
use \Datetime;

/**
 * Class DashboardController.
 */
class AddServerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.user.addserver');
    }

    public function create(Request $request)
    {   
        $database = $request->database;
        $url = $request->url;
        $server_url = DB::table('monitors')
            ->where('url', '=', $url)
            ->first();
        if ($server_url !== null) {
            throw new GeneralException(__('exceptions.frontend.auth.url'));
        }
        $submit = $request->status;
        $ip = $request->ip;
        $data_name = $request->database_name;
        $data_port = $request->port;
        $data_user = $request->database_username;
        $data_pass = $request->database_password;
        if($submit == 2) {
            if ($database == 'Mysql'){
                $conn = @mysqli_connect($ip, $data_user, $data_pass);
                if (!$conn) {
                    return redirect()->back()->withInput()->withFlashDanger(__('strings.frontend.user.database_error'));
                }else{
                    return redirect()->back()->withInput()->withFlashSuccess(__('strings.frontend.user.database_connect'));
                }
            }else {
                $dbconn3 = @pg_connect("host=$ip port=$data_port dbname=$data_name user=$data_user password=$data_pass");
                if (!$dbconn3) {
                    return redirect()->back()->withInput()->withFlashDanger(__('strings.frontend.user.database_error'));
                }else{
                    return redirect()->back()->withInput()->withFlashSuccess(__('strings.frontend.user.database_connect'));
                }
            }

        
        }else {
        
            $url = $request->input('url');
            $http = parse_url($url, PHP_URL_SCHEME);
            $ip = $request->input('ip');
            $metric = '?q=[{"p":"http","h":"'.$ip.':44323","hs":"localhost","ci":"_all","cl"';
            $host_utilization = ':%5B"cpu-utilization","disk-latency","memory-utilization","network-throughput"%5D%7D%5D';
            $disk = ':%5B"disk-iops","disk-latency","disk-throughput","disk-utilization"%5D%7D%5D';
            $label = $request->label;
            
            if ($http == 'http'){
                $http_port = 80;
            }else {
                $http_port = 443;
            }

            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_HEADER, true);
            curl_setopt($curlHandle, CURLOPT_NOBODY  , true); 
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_exec($curlHandle);
            $response = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            $headers = explode("\n", curl_exec($curlHandle));
            curl_close($curlHandle);
            $host = $headers[0];
            if ($host != ""){
                $m_array = array_keys(preg_grep('/^Server\b/i', $headers));
                if (!array_filter($m_array)){
                    $s_array = array_keys(preg_grep('/^server\b/i', $headers));
                    $array = $s_array[0];
                    $web = $headers[$array];
                    $web_ser = explode(":", $web);
                }else {
                    $array = $m_array[0];
                    $web = $headers[$array];
                    $web_ser = explode(":", $web);
                }
                $web_s = $web_ser[1];
                if (is_array($web_s)){
                    $web_server = $web_s[0];
                }else {
                    $web_server = $web_s;
                }
            }else{
                $web_server = 'N/A';
            }
            if ($response >= 99 && $response <= 290){
                $api_code = $response;
                $api_status = 'up';
                $api_output = 'API server '.$label.'  is RUNNING: ip = '.$ip.', with response code = '.$response.', the request was successfully received, understood, and accepted.';

            }else if ($response >= 290 && $response <= 390){
                $api_code = $response;
                $api_status = 'redirection';
                $api_output = 'API server '.$label.' is REDIRECT: ip = '.$ip.', with response code = '.$response.', further action needs to be taken in order to complete the request.';

            }else if ($response == 0){
                $api_code = 'N/A';
                $api_status = 'down';
                $api_output = 'API server '.$label.'  is DOWN: ip = '.$ip.', with response code = '.$response.', the server failed to fulfil an apparently valid request.';
            }else {
                $api_code = $response;
                $api_status = 'down';
                $api_output = 'API server '.$label.'  is DOWN: ip = '.$ip.', with response code = '.$response.', the server failed to fulfil an apparently valid request.';
            }
            $timeout = $request->input('timeout');
            $time = date('Y-m-d H:i:s');
            $milisecond = $timeout * 1000;
            $ping = exec('ping -c 5 '.gethostbyname($ip), $output, $result);

            if ($result == 0){

                $chunks = explode(' ', $ping);
                $avg = $chunks[3];
                $preg_avg = preg_match_all('~(?:\d+(?:\.\d+)?|\w)+|[^\s\w]~', $avg, $matches);
                $laten = $matches[0];
                $ms = ceil($laten[2]);
                $status = 'up';
                $status_output = 'Network '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', the response was successfully received.';
                $latency = $ms;
            }else {
                $status = 'down';
                $status_output = 'Network '.$label.'  is DOWN: ip = '.$ip.', with port = '.$http_port.', TIMEOUT ERROR: no response from server.';
                $latency = 0;
            }
            
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
            DB::table('monitors')
                ->insert([
                    'url'=>$request->input('url'),
                    'created_at'=>$time,
                    'updated_at'=>$time,
                    'certificate_check_enabled'=>true,
                    'uptime_status_last_change_date'=>$time,
                    'pattern'=>$latency,
                    'label'=>$request->input('label'),
                    'ip'=>$request->input('ip'),
                    'database'=>$request->database,
                    'database_name'=>$request->database_name,
                    'database_port'=>$request->port,
                    'database_username'=>$request->database_username,
                    'database_password'=>$request->database_password,
                    'database_status'=>$data_status,
                    'database_output'=>$data_output,
                    'api'=>$api_status,
                    'api_code'=>$api_code,
                    'api_output'=>$api_output,
                    'web_server'=>$web_server,
                    'metric'=>$metric,
                    'timeout'=>$request->input('timeout'),
                    'email'=>$request->input('email'),
                    'sms'=>$request->input('sms'),
                    'active'=>'Yes',
                    'status'=>$status,
                    'status_log'=>$status_output,
                    'host_utilization'=>$host_utilization,
                    'disk'=>$disk,
                    ]);

            $uptime = Artisan::call('monitor:check-uptime');
            $ssl = Artisan::call('monitor:check-certificate');
            
            $server_all = DB::table('monitors')->where('url', $url)->get();
            for ($i=0; $i<count($server_all); $i++) {
                $label = $server_all[$i]->label;
                $ip = $server_all[$i]->ip;
                $uptime = $server_all[$i]->uptime_status;
                $url = $server_all[$i]->url;
                $http = parse_url($url, PHP_URL_SCHEME);
                $time = date('Y-m-d H:i:s');

                DB::table('log_server')
                    ->insert([
                        'status'=>$server_all[$i]->status,
                        'status_log'=>$server_all[$i]->status_log,
                        'services'=>'Network',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);

                if ($http == 'http'){
                    $http_port = 80;
                }else {
                    $http_port = 443;
                }

                if ($uptime == 'up'){
                    $uptime_output = 'Web server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', the response was successfully received.';
                }else {
                    $uptime_failure = $server_all[$i]->uptime_check_failure_reason;
                    $uptime_output = 'Web server '.$label.'  is RUNNING: ip = '.$ip.', operation timeout. The specified time-out period was reached according to the conditions.';
                }

                DB::table('log_server')
                    ->insert([
                        'status'=>$uptime,
                        'status_log'=>$uptime_output,
                        'services'=>'Web Server',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>date('Y-m-d H:i:s', strtotime('+1 second')),
                        'updated_at'=>date('Y-m-d H:i:s', strtotime('+1 second')),
                    ]);

                $api = $server_all[$i]->api;
                DB::table('log_server')
                    ->insert([
                        'status'=>$api,
                        'status_log'=>$server_all[$i]->api_output,
                        'services'=>'API',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>date('Y-m-d H:i:s', strtotime('+2 second')),
                        'updated_at'=>date('Y-m-d H:i:s', strtotime('+2 second')),
                    ]);
                
                $database = $server_all[$i]->database_status;
                DB::table('log_server')
                    ->insert([
                        'status'=>$database,
                        'status_log'=>$server_all[$i]->database_output,
                        'services'=>'Database',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>date('Y-m-d H:i:s', strtotime('+3 second')),
                        'updated_at'=>date('Y-m-d H:i:s', strtotime('+3 second')),
                    ]);

                $ssl = $server_all[$i]->certificate_status;
                $ssl_date = $server_all[$i]->certificate_expiration_date;
                if ($ssl == 'invalid'){
                    $ssl_status = 'Not Applicable';
                    $ssl_output = 'SSL Certificate server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.' because SSL Certificate is not available.';

                }else {
                    $ssl_status = 'Applicable';
                    $ssl_output = 'SSL Certificate server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', the expiration date is '.$ssl_date;
                }
                DB::table('log_server')
                    ->insert([
                        'status'=>$ssl_status,
                        'status_log'=>$ssl_output,
                        'services'=>'SSL Cert',
                        'server_id'=>$server_all[$i]->id,
                        'created_at'=>date('Y-m-d H:i:s', strtotime('+4 second')),
                        'updated_at'=>date('Y-m-d H:i:s', strtotime('+4 second')),
                    ]);

                DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                    'uptime_before_status'=>$uptime,
                    'certificate_before_status'=>$ssl,
                ]);
            }
            
            return redirect()->route('frontend.user.server')->withFlashSuccess(__('strings.frontend.user.server_created'));
            
        }
    }

    public function edit($id)
    {
        $server_all = DB::table('monitors')->find($id);
        return view('frontend.user.editserver' , compact('server_all'));
    }

    public function update(Request $request)
    {   
        $url = $request->url;
        $server_own = DB::table('monitors')
            ->where('id', $request->input('id'))
            ->get();

        $server_url = DB::table('monitors')
            ->where('url', '=', $url)
            ->first();

        if ($server_own[0]->url !== $url) {
            if ($server_url !== null) {
                throw new GeneralException(__('exceptions.frontend.auth.url'));
            }
        }
        $database = $request->database;
        $submit = $request->status;
        $label = $request->label;
        $ip = $request->ip;
        $metric = '?q=[{"p":"http","h":"'.$ip.':44323","hs":"localhost","ci":"_all","cl"';
        $time = date('Y-m-d H:i:s');
        $data_name = $request->database_name;
        $data_port = $request->port;
        $data_user = $request->database_username;
        $data_pass = $request->database_password;
        if($submit == 2) {
            if ($database == 'Mysql'){
                $conn = @mysqli_connect($ip, $data_user, $data_pass);
                if (!$conn) {
                    return redirect()->back()->withInput()->withFlashDanger(__('strings.frontend.user.database_error'));
                }else{
                    return redirect()->back()->withInput()->withFlashSuccess(__('strings.frontend.user.database_connect'));
                }
            }else {
                $dbconn3 = @pg_connect("host=$ip port=$data_port dbname=$data_name user=$data_user password=$data_pass");
                if (!$dbconn3) {
                    return redirect()->back()->withInput()->withFlashDanger(__('strings.frontend.user.database_error'));
                }else{
                    return redirect()->back()->withInput()->withFlashSuccess(__('strings.frontend.user.database_connect'));
                }
            }

        
        }else {

            $server_all = DB::table('monitors')->where('id', $request->input('id'))->update([
                'label'=>$request->input('label'),
                'url'=>$request->input('url'),
                'ip'=>$request->input('ip'),
                'timeout'=>$request->input('timeout'),
                'email'=>$request->input('email'),
                'sms'=>$request->input('sms'),
                'database'=>$request->database,
                'database_name'=>$request->database_name,
                'database_port'=>$request->port,
                'database_username'=>$request->database_username,
                'database_password'=>$request->database_password,
                'updated_at'=>$time,
                'metric'=>$metric,
            ]);
            return redirect()->route('frontend.user.server')->withFlashSuccess(__('strings.frontend.user.server_updated'));
        }
    }

    public function destroy($id)
    {
        $server_all = DB::table('monitors')->delete($id);
        return redirect()->route('frontend.user.server')->withFlashSuccess(__('strings.frontend.user.server_deleted'));
    }

    public function active($id)
    {
        $server_all = DB::table('monitors')->where('id', $id)->update([
            'active'=>'Yes',
        ]);
        return redirect()->route('frontend.user.server')->withFlashSuccess(__('strings.frontend.user.server_active'));
    }

    public function deactive($id)
    {
        $server_all = DB::table('monitors')->where('id', $id)->update([
            'active'=>'No',
        ]);
        return redirect()->route('frontend.user.server')->withFlashSuccess(__('strings.frontend.user.server_deactive'));
    }


}
