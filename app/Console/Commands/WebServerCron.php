<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Auth;
use Artisan;
use Mail;
use Carbon\Carbon;

class WebServerCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:webserver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check every minute the status of Webserver services of the server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $server_all = DB::table('monitors')->get();
        for ($i=0; $i<count($server_all); $i++) {
            $time = date('Y-m-d H:i:s');
            $url = $server_all[$i]->url;
            $ip = $server_all[$i]->ip;
            $label = $server_all[$i]->label;
            $email = $server_all[$i]->email;
            $sms = $server_all[$i]->sms;
            $http = parse_url($url, PHP_URL_SCHEME);
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

            if ($server_all[$i]->web_server == 'N/A'){
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

                DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                    'web_server'=>$web_server,
                    ]);
            }
            $uptime_before = $server_all[$i]->uptime_before_status;
            $uptime_after = $server_all[$i]->uptime_status;

            if ($uptime_after != $uptime_before){

                if ($uptime_after == 'up'){
                    $uptime_output = 'Web server '.$label.'  is RUNNING: ip = '.$ip.', with port = '.$http_port.', the response was successfully received.';
                }else {
                    $uptime_failure = $server_all[$i]->uptime_check_failure_reason;
                    $uptime_output = 'Web server '.$label.'  is DOWN: ip = '.$ip.', operation timeout. The specified time-out period was reached according to the conditions.';
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

                $user = DB::table('server_user')->where('server_id', $server_all[$i]->id)
                    ->leftJoin('users', 'server_user.user_id', '=', 'users.id')
                    ->get();

                define ("HOST_NAME", "mtsms.15888.my/Receiver.aspx");
                
                for ($i=0; $i<count($user); $i++) {
                    if ($email == 'Yes' && $sms == 'Yes'){

                        $keyword = "INSTUN";
                        $Username = "instunbulk";
                        $Password = "instunPwd2017";
                        $SMSType = "bulk";
                        $message = 'INSTUN - '.$uptime_output;
                        $Message = urlencode($message);       // monitors->status_log
                        $Phone = $user[$i]->mobile;
                        $MsgID = 0;
                        $url =
                        "keyword=$keyword&Username=$Username&Password=$Password&Type=$SMSType&contents=$Message&MobileNo=$Phone&guid=$MsgID";
                        $url = "http://".HOST_NAME."?".$url;
                        $var_array = file($url);
                        
                        $first_name = $user[$i]->first_name;
                        $last_name = $user[$i]->last_name;
                        $name = $first_name.' '.$last_name;
                        $subject = 'ASMOS - Web Server '.$label.' is Down';
                        $to_name = $name;
                        $to_email = $user[$i]->email;
                        $data = array(
                            'name'=> $name, 
                            'label'=> $label, 
                            'output' => $uptime_output
                        );
                        Mail::send('emails.email', $data, function($message) use ($to_name, $to_email, $subject) {
                        $message->to($to_email, $to_name)
                        ->subject($subject);
                        $message->from('ASMOS@example.com','ASMOS');
                        });

                    } else if ($email == 'Yes' && $sms != 'Yes'){

                        $first_name = $user[$i]->first_name;
                        $last_name = $user[$i]->last_name;
                        $name = $first_name.' '.$last_name;
                        $subject = 'ASMOS - Web Server '.$label.' is Down';
                        $to_name = $name;
                        $to_email = $user[$i]->email;
                        $data = array(
                            'name'=> $name, 
                            'label'=> $label, 
                            'output' => $uptime_output
                        );
                        Mail::send('emails.email', $data, function($message) use ($to_name, $to_email, $subject) {
                        $message->to($to_email, $to_name)
                        ->subject($subject);
                        $message->from('ASMOS@example.com','ASMOS');
                        });
                    
                    } else if ($email != 'Yes' && $sms == 'Yes'){
                        
                        $keyword = "INSTUN";
                        $Username = "instunbulk";
                        $Password = "instunPwd2017";
                        $SMSType = "bulk";
                        $message = 'INSTUN - '.$uptime_output;
                        $Message = urlencode($message);
                        $Phone = $user[$i]->mobile;
                        $MsgID = 0;
                        $url =
                        "keyword=$keyword&Username=$Username&Password=$Password&Type=$SMSType&contents=$Message&MobileNo=$Phone&guid=$MsgID";
                        $url = "http://".HOST_NAME."?".$url;
                        $var_array = file($url);

                    } else {
                        
                    }
                }
            }
        }
        $this->info('Webserver run successfully!');
    }
}
