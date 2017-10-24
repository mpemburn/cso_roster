<?php
namespace App\Services;

use App\Contracts\Services\MemberServiceContract;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Mail;

/**
 * Class MemberService
 * @package App\Services
 */
class MemberService implements MemberServiceContract
{

    public function getMemberEmailFromId($memberId)
    {
        $member = Member::find($memberId);

        return (!is_null($member)) ? $member->email : null;
    }

    /**
     * @param $email
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getMemberFromEmail($email)
    {
        $member = Member::where('email', $email)->first();

        return $member;
    }

    /**
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    function getMemberFromUserId($user_id)
    {
        $member = Member::where('user_id', $user_id)->first();

        return $member;
    }

    /**
     * @param $user_id
     * @return integer|null
     */
    function getMemberIdFromUserId($user_id)
    {
        $member = $this->getMemberFromUserId($user_id);

        return (!is_null($member)) ? $member->id : null;
    }

    /**
     * @param $user_id
     * @return integer|null
     */
    function getMemberIdFromUserResetToken($token)
    {
        $user = User::where('reset_token', $token)->first();
        if (!is_null($user)) {
            return $this->getMemberIdFromUserId($user->id);
        }
        return false;
    }

    /**
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    function getUserFromMemberId($member_id)
    {
        $member = Member::find($member_id);
        if (!is_null($member->user_id)) {
            $user = User::find($member->user_id);
            return $user;
        }
        return null;
    }

    /**
     * @param $email
     * @return MemberService|\Illuminate\Database\Eloquent\Model|null|static
     */
    function getUserFromMemberEmailAddress($email)
    {
        $member = $this->getMemberFromEmail($email);

        if (!is_null($member)) {
            return $this->getUserFromMemberId($member->id);
        }

        return null;
    }

    /**
     * @param $email
     * @return bool
     */
    public function isValidMemberEmailAddress($email)
    {
        $foundMember = $this->getMemberFromEmail($email);

        return (!is_null($foundMember));
    }

    /**
     * @param $request
     */
    public function resetUserPassword($request)
    {
        $data = $request->all();
        $memberId = $data['member_id'];
        $user = $this->getUserFromMemberId($memberId);

        $rules = [
            'old_password' => 'required|matches_old_password',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $user->password = Hash::make($data['password']);
            $user->save();
            $response = ['status' => 'success'];
        }

        return $response;
    }

    public function sendPasswordResetEmail(Request $request)
    {
        $data = $request->all();
        $user = $this->getUserFromMemberEmailAddress($data['email']);

        if (!is_null($user)) {
            // Create a unique hash based on user name and date
            $hash = base64_encode(Hash::make($user->name . date('MdYHis', time())));
            $resetLink = url('/') . '/password/token/' . $hash;
            $appName = config('app.name');
            // Save the hash into the user table (will be retrieved to complete reset process).
            $user->reset_token = $hash;
            $user->save();

            Mail::send('emails.reset_password', [
                'user_name' => $user->name,
                'app_name' => $appName,
                'password_reset_link' => $resetLink
            ], function ($mailer) use ($user) {
                $domain = str_replace(['http://', 'https://'], '', url('/'));
                $mailer->from('noreply@' . $domain, 'Your Application');
                $mailer->to($user->email, $user->name)->subject('Password Reset');
            });
        }

    }
}