<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Auth\UserRepository;
use DB;
use Auth;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
    {   
        $user = Auth::user();
        $email = $request->email;
        $mobile = $request->mobile;
        $user_all = DB::table('users')
            ->where('email', '=', $email)->first();
        if ($user->email !== $email) {
            if ($user_all !== null) {
                return redirect()->route('frontend.user.account')->withFlashDanger(__('exceptions.frontend.auth.email_taken'));
            }
        }
        if ((substr($mobile, 0, 1) !== '6')){
            return redirect()->route('frontend.user.account')->withFlashDanger(__('exceptions.frontend.auth.mobile_start'));
        }
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('first_name', 'last_name', 'username', 'mobile', 'email', 'role')
        );

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }
}
