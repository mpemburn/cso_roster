<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Contracts\Services\MemberServiceContract;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register/success';

    /**
     * @memberService MemberServiceContract
     */
    protected $memberService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MemberServiceContract $memberService)
    {
        $this->memberService = $memberService;
        $this->middleware('guest');
    }

    public function success()
    {
        // Get memberId stored in the 'create' method
        $memberId = session('memberId');
        // Get member email.  Returns null if not found
        $email = $this->memberService->getMemberEmailFromId($memberId);
        // Zap the memberId so that this won't show success with repeated calls
        session(['memberId' => null]);

        return view('auth/login', ['registered' => $email, 'email' => $email]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|member_email_found|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|invalid_pattern|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $member = $this->memberService->getMemberFromEmail($data['email']);
        $memberName = $member->first_name . ' ' . $member->last_name;
        session(['memberId' => $member->id]);

        $newUser = User::create([
            'name' => $memberName,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Set the new User id into the Member record
        $member->user_id = $newUser->id;
        $member->save();

        // return a blank instance of User,
        // otherwise, the newly created user will login automatically
        return new User;
    }

}
