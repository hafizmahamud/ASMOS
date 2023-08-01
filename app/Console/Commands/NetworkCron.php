<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DB;
use Auth;
use Artisan;
use Mail;
use Carbon\Carbon;

class NetworkCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:network';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check every minute the status of network of the server';

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
            $email = $server_all[$i]->email;
            $sms = $server_all[$i]->sms;
            $url = $server_all[$i]->url;
            $http = parse_url($url, PHP_URL_SCHEME);
            if ($http == 'http'){
                $http_port = 80;
            }else {
                $http_port = 443;
            }
            $ip = $server_all[$i]->ip;
            $label = $server_all[$i]->label;
            $timeout = $server_all[$i]->timeout;
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
                        $message = 'INSTUN - '.$status_output;
                        $Message = urlencode($message);        // monitors->status_log
                        $Phone = $user[$i]->mobile;
                        $MsgID = 0;
                        $url =
                        "keyword=$keyword&Username=$Username&Password=$Password&Type=$SMSType&contents=$Message&MobileNo=$Phone&guid=$MsgID";
                        $url = "http://".HOST_NAME."?".$url;
                        $var_array = file($url);
                        
                        $first_name = $user[$i]->first_name;
                        $last_name = $user[$i]->last_name;
                        $name = $first_name.' '.$last_name;
                        $subject = 'ASMOS - Network Server '.$label.' Down';
                        $to_name = $name;
                        $to_email = $user[$i]->email;
                        $data = array(
                            'name'=> $name, 
                            'label'=> $label, 
                            'output' => $status_output
                        );
                        Mail::send('emails.email', $data, function($message) use ($to_name, $to_email, $subject) {
                        $message->to($to_email, $to_name)
                        ->subject($subject);
                        $message->from('ASMOS@example.com','ASMOS');
                        });

                    }else if ($email == 'Yes' && $sms != 'Yes'){

                        $first_name = $user[$i]->first_name;
                        $last_name = $user[$i]->last_name;
                        $name = $first_name.' '.$last_name;
                        $subject = 'ASMOS - Network Server '.$label.' Down';
                        $to_name = $name;
                        $to_email = $user[$i]->email;
                        $data = array(
                            'name'=> $name, 
                            'label'=> $label, 
                            'output' => $status_output
                        );
                        Mail::send('emails.email', $data, function($message) use ($to_name, $to_email, $subject) {
                        $message->to($to_email, $to_name)
                        ->subject($subject);
                        $message->from('ASMOS@example.com','ASMOS');
                        });
                        
                    }else if ($email != 'Yes' && $sms == 'Yes'){

                        $keyword = "INSTUN";
                        $Username = "instunbulk";
                        $Password = "instunPwd2017";
                        $SMSType = "bulk";
                        $message = 'INSTUN - '.$status_output;
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

            DB::table('monitors')->where('id', $server_all[$i]->id)->update([
                'pattern'=>$latency,
            ]);

            DB::table('server_latency')
                ->insert([
                    'status'=>$status,
                    'latency'=>$latency,
                    'server_id'=>$server_all[$i]->id,
                    'created_at'=>$time,
                    'updated_at'=>$time,
                ]);
        }
        $this->info('Network run successfully!');
    }
}
