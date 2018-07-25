<?php
namespace App\Services;

use App\Contracts\Services\MemberServiceContract;
use App\Contracts\Repositories\MemberRepositoryContract;
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
    protected $member;
    protected $user;

    public function __construct(Member $member, User $user)
    {
        $this->member = $member;
        $this->user = $user;
    }

    public function createMember(Request $request)
    {
        $data = $request->all();
        $rules = $this->member->rules;

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $data['member_since'] = date('Y-m-d H:i:s', time());
            $response = $this->member->fill($data)->save();
        }

        return $response;
    }

    public function getMemberEmailFromId($memberId)
    {
        $member = $this->member->find($memberId);

        return (!is_null($member)) ? $member->email : null;
    }

    /**
     * @param $email
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getMemberFromEmail($email)
    {
        $member = $this->member->where('email', $email)->first();

        return $member;
    }

    /**
     * @param $email
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getMemberFromEmailAndZip($email, $zip)
    {
        $member = $this->member->where('email', $email)
            ->where('zip', $zip)
            ->first();

        return $member;
    }

    /**
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    function getMemberFromUserId($user_id)
    {
        $member = $this->member->where('user_id', $user_id)->first();

        return $member;
    }

    /**
     * @param $user_id
     * @return integer|null
     */
    public function getMemberIdFromUserId($user_id)
    {
        $member = $this->getMemberFromUserId($user_id);

        return (!is_null($member)) ? $member->id : null;
    }

    /**
     * @param $token
     * @return Member
     */
    public function getMemberFromUserResetToken($token)
    {
        $user = $this->user->where('reset_token', $token)->first();
        if (!is_null($user)) {
            return $this->getMemberFromUserId($user->id);
        }

        return false;
    }

    /**
     * @param $token
     * @return string|bool
     */
    public function getMemberEmailFromUserResetToken($token)
    {
        $member = $this->getMemberFromUserResetToken($token);
        if (!is_null($member)) {
            return $member->email;
        }
        return false;
    }

    /**
     * @param $token
     * @return string|bool
     */
    public function getMemberIdFromUserResetToken($token)
    {
        $member = $this->getMemberFromUserResetToken($token);
        if (!is_null($member) && $member !== false) {
            return $member->id;
        }
        return false;
    }

    /**
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getUserFromMemberId($member_id)
    {
        $member = $this->member->find($member_id);
        if (!is_null($member->user_id)) {
            $user = $this->user->find($member->user_id);
            return $user;
        }
        return null;
    }

    /**
     * @param $email
     * @return MemberService|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function getUserFromMemberEmailAddress($email)
    {
        $member = $this->getMemberFromEmail($email);

        if (!is_null($member)) {
            return $this->getUserFromMemberId($member->id);
        }

        return null;
    }

    public function getMemberFromPhoneNumber($phone)
    {
        $phone = preg_replace("/^\+?1/", '',$phone);

        $member = $this->member->where('home_phone', $phone)
            ->orWhere('cell_phone', $phone)
            ->first();

        return $member;
    }

    /**
     * @param $email
     * @param $zip
     * @return MemberService|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function getUserFromMemberEmailAndZip($email, $zip)
    {
        $member = $this->getMemberFromEmailAndZip($email, $zip);

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
        $nextUrl = '/password/success';

        $rules = [
            'password' => 'required|invalid_pattern|confirmed',
            'password_confirmation' => 'required',
        ];

        // if this is being submitted by logged-in user,'old_password' will be present
        if (isset($data['old_password'])) {
            $rules['old_password'] = 'required|matches_old_password';
            $nextUrl = '/profile/success';

        }
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $user->password = Hash::make($data['password']);
            // Delete the reset token so that it can't be used again.
            $user->reset_token = '';
            $user->save();
            $response = [
                'status' => 'success',
                'data' => ['url' => url('/') . $nextUrl]
            ];
        }

        return $response;
    }

    public function sendPasswordResetEmail(Request $request)
    {
        $data = $request->all();

        $rules = [
            'email' => 'required|member_email_found|string|email|max:255'
        ];
        $response = null;

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $user = $this->getUserFromMemberEmailAddress($data['email']);
            if (!is_null($user)) {
                // Create a unique hash based on user name and date
                $hash = base64_encode(Hash::make($user->name . date('MdYHis', time())));
                $resetLink = url('/') . '/password/token/' . $hash;
                $appName = config('app.name');
                // Save the hash into the user table (will be retrieved to complete reset process).
                $user->reset_token = $hash;
                $user->save();

                // Send the email
                $mailResult = $this->mailSendResetLink($user, $appName, $resetLink);

                if ($mailResult == 'success') {
                    $response = [
                        'status' => 'success',
                        'data' => ['url' => url('/') . '/password/sent_success/' . $hash]
                    ];
                } else {
                    $response = [
                        'errors' => $mailResult,
                    ];
                }
            }
        }

        return $response;
    }

    // PROTECTED Methods

    /**
     * @param $user
     * @param $appName
     * @param $resetLink
     * @return array|string
     */
    protected function mailSendResetLink($user, $appName, $resetLink)
    {
        $failed = [];
        try {
            Mail::send('emails.reset_password', [
                'user_name' => $user->name,
                'app_name' => $appName,
                'password_reset_link' => $resetLink
            ], function ($mailer) use ($user, $appName) {
                $domain = str_replace(['http://', 'https://'], '', url('/'));
                $mailer->from(config('mail.from.address') , $appName . ' Team');
                $mailer->to($user->email, $user->name)->subject('Password Reset');
            });
            $failed = Mail::failures();
        } catch (\Exception $e) {
            return ['email' => 'Unable to send to this address'];
        }

        return (count($failed) == 0) ? 'success' : $failed;
    }
}