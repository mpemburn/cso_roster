<?php
namespace App\Services;

use App\Contracts\Services\MemberServiceContract;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;


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
     * @param $user_id
     * @return integer|null
     */
    function getMemberIdFromUserId($user_id)
    {
        $member = $this->getMemberFromUserId($user_id);

        return (!is_null($member)) ? $member->id : null;
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
            'password' => 'required',
            'password_confirmation' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $user->password = Hash::make($data['password']);
            $user->save();
            $response = ['response' => 'success'];
        }

        return $response;
    }

}