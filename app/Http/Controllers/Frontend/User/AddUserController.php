<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use DB;
use app\Models\Auth\BaseUser;

/**
 * Class DashboardController.
 */
class AddUserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user_all = DB::table('server_user')
                ->join('monitors', 'server_user.server_id', '=', 'monitors.id')
                ->join('users', 'server_user.user_id', '=', 'users.id')
                ->get()
                ->groupBy('user_id');

        $user_s = DB::table('server_user')
                ->distinct('user_id')
                ->pluck('user_id')->toArray();

        $user_exclude = DB::table('users')
                ->whereNotIn('id',$user_s)
                ->get();
                
        return view('frontend.user.adduser', compact('user_all', 'user_exclude'));
    }

    public function edit($id)
    {   
        $server_all = DB::table('monitors')->get();
        $user_all = DB::table('users')->find($id);
        $server_own = DB::table('server_user')->where('user_id', $id)
                ->get();
        if(count($server_own) == 0){
            $array_server = [];
        }else {
        
            for ($i=0; $i<count($server_own); $i++) {
                $server_id = $server_own[$i]->server_id;
                $array_server[] = $server_own[$i]->server_id;
            }
        }
        $server = $array_server;
        return view('frontend.user.editUser' , compact('user_all','server_all','server'));
    }

    public function update(Request $request)
    {   
        $mobile = $request->mobile;
        $email = $request->email;
        $user_own = DB::table('users')
            ->where('id', $request->input('id'))
            ->get();
        $user_email = DB::table('users')
            ->where('email', '=', $email)
            ->first();

        if ($user_own[0]->email !== $email) {
            if ($user_email !== null) {
                throw new GeneralException(__('exceptions.frontend.auth.email_taken'));
            }
        }
        if ((substr($mobile, 0, 1) !== '6')){
            throw new GeneralException(__('exceptions.frontend.auth.mobile_start'));;
        }
        $user_all = DB::table('users')->where('id', $request->input('id'))->update([
            'first_name'=> $request->input('first_name'),
            'last_name'=> $request->input('last_name'),
            'username'=> $request->input('username'),
            'mobile'=> $request->input('mobile'),
            'email'=> $request->input('email'),
            'role'=> $request->input('role'),
        ]);

        $server_own = DB::table('server_user')->where('user_id', $request->input('id'))
                ->get();
        if(count($server_own) == 0){
            $serverN = $request->servername;
            $time = date('Y-m-d H:i:s');
            foreach ($serverN as $ser){
                DB::table('server_user')
                    ->insert([
                        'user_id'=>$request->input('id'),
                        'server_id'=>$ser,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);
            }
        }else {
            DB::table('server_user')->where('user_id', $request->input('id'))->delete();
            $serverN = $request->servername;
            $time = date('Y-m-d H:i:s');
            foreach ($serverN as $ser){
                DB::table('server_user')
                    ->insert([
                        'user_id'=>$request->input('id'),
                        'server_id'=>$ser,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);
            }
        }
        return redirect()->route('frontend.user.adduser')->withFlashSuccess(__('strings.frontend.user.profileuser_updated'));
    }

    public function destroy($id)
    {
        $user_all = DB::table('users')->delete($id);
        return redirect()->route('frontend.user.adduser')->withFlashSuccess(__('strings.frontend.user.profileuser_deleted'));
    }

    public function active($id)
    {
        $user_all = DB::table('users')->where('id', $id)->update([
            'active'=>1,
        ]);
        return redirect()->route('frontend.user.adduser')->withFlashSuccess(__('strings.frontend.user.user_active'));
    }

    public function deactive($id)
    {
        $user_all = DB::table('users')->where('id', $id)->update([
            'active'=>0,
        ]);
        return redirect()->route('frontend.user.adduser')->withFlashSuccess(__('strings.frontend.user.user_deactive'));
    }


}
