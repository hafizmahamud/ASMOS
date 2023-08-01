<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DB;
use Auth;
use Artisan;
use Mail;
use Carbon\Carbon;

class ApiCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check every minute the status of api services of the server';

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
            $url = $server_all[$i]->url;
            $email = $server_all[$i]->email;
            $sms = $server_all[$i]->sms;
            $label = $server_all[$i]->label;
            $timeout = $server_all[$i]->timeout;
            $ip = $server_all[$i]->ip;
            $time = date('Y-m-d H:i:s');
            $api = $server_all[$i]->api;
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
            curl_close($curlHandle);

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
                    'api_code'=>$api_code,
                    'api_output'=>$api_output,
                    ]);
            }
        }
        $this->info('Api run successfully!');

    }
}