<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Events\Frontend\Auth\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Exceptions\GeneralException;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(home_route());
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        abort_unless(config('access.registration'), 404);

        $server_all = DB::table('monitors')->get();
        return view('frontend.auth.register', compact('server_all'));
    }

    /**
     * @param RegisterRequest $request
     *
     * @throws \Throwable
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        abort_unless(config('access.registration'), 404);
        $role = $request->role;
        $serverN = $request->servername;
        $mobile = $request->mobile;
        if ($role == "user"){
            if ($serverN == '') {
                throw new GeneralException(__('exceptions.frontend.auth.server_assigned'));
            }
        }
        if ((substr($mobile, 0, 1) !== '6')){
            throw new GeneralException(__('exceptions.frontend.auth.mobile_start'));
        }
        $time = date('Y-m-d H:i:s');
        $user = $this->userRepository->create($request->only('first_name', 'last_name', 'username', 'mobile', 'email', 'role', 'password'));

        if ($role == "user"){
            foreach ($serverN as $ser){
                DB::table('server_user')
                    ->insert([
                        'user_id'=>$user->id,
                        'server_id'=>$ser,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);
            }
        } else {
            $server_all = DB::table('monitors')->get();
            for ($i=0; $i<count($server_all); $i++) {

                $id = $server_all[$i]->id;
                DB::table('server_user')
                    ->insert([
                        'user_id'=>$user->id,
                        'server_id'=>$id,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ]);
            }
        }

        DB::table('users')->where('id', $user->id)->update([
            'confirmed'=> true,
        ]);

        // If the user must confirm their email or their account requires approval,
        // create the account but don't log them in.
        if (config('access.users.confirm_email') || config('access.users.requires_approval')) {
            event(new UserRegistered($user));

            return redirect($this->redirectPath())->withFlashSuccess(
                config('access.users.requires_approval') ?
                    __('exceptions.frontend.auth.confirmation.created_pending') :
                    __('exceptions.frontend.auth.confirmation.created_confirm')
            );
        }

        auth()->login($user);

        event(new UserRegistered($user));

        return redirect('/dashboard');
    }
}
