<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DB;
use Auth;
use Artisan;
use Mail;
use Carbon\Carbon;

class DatabaseCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check every two minutes the status of database services of the server';

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
            $database = $server_all[$i]->database;
            $data_name = $server_all[$i]->database_name;
            $data_user = $server_all[$i]->database_username;
            $data_pass = $server_all[$i]->database_password;
            $data_port = $server_all[$i]->database_port;
            $ip = $server_all[$i]->ip;
            $email = $server_all[$i]->email;
            $sms = $server_all[$i]->sms;
            $label = $server_all[$i]->label;
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
                        $message = 'INSTUN - '.$data_output;
                        $Message = urlencode($message);
                        $Phone = $user[$i]->mobile;
                        $MsgID = 0;
                        $url =
                        "keyword=$keyword&Username=$Username&Password=$Password&Type=$SMSType&contents=$Message&MobileNo=$Phone&guid=$MsgID";
                        $url = "http://".HOST_NAME."?".$url;
                        $var_array = file($url);

                        $first_name = $user[$i]->first_name;
                        $last_name = $user[$i]->last_name;
                        $name = $first_name.' '.$last_name;
                        $subject = 'ASMOS - Database Server '.$label.' Down';
                        $to_name = $name;
                        $to_email = $user[$i]->email;
                        $data = array(
                            'name'=> $name, 
                            'label'=> $label, 
                            'output' => $data_output
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
                        $subject = 'ASMOS - Database Server '.$label.' Down';
                        $to_name = $name;
                        $to_email = $user[$i]->email;
                        $data = array(
                            'name'=> $name, 
                            'label'=> $label, 
                            'output' => $data_output
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
                        $message = 'INSTUN - '.$data_output;
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
        $this->info('Database run successfully!');
    }
}
