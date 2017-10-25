<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Contracts\Services\MemberServiceContract;

/**
 * Class ForgotPasswordController
 * @package App\Http\Controllers\Auth
 */
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSendResetRequestForm()
    {
        return view('auth/passwords/send_reset', []);
    }

    /**
     * @param Request $request
     * @param MemberServiceContract $memberService
     */
    public function sendResetLinkEmail(Request $request, MemberServiceContract $memberService)
    {
        $memberService->sendPasswordResetEmail($request);
    }

    /**
     * @param $token
     * @return string
     */
    public function showPasswordResetForm($token, MemberServiceContract $memberService)
    {
        $memberId = $memberService->getMemberIdFromUserResetToken($token);

        if ($memberId !== false) {
            return view('auth/passwords/token_reset', [
                'member_id' => $memberId
            ]);
        } else {
            return view('auth/passwords/token_expired');
        }
    }

    public function submitNewPassword(Request $request, MemberServiceContract $memberService)
    {
        return $memberService->resetUserPassword($request);
    }
}
