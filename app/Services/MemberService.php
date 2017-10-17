<?php
namespace App\Services;

use App\Contracts\Services\MemberServiceContract;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


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
        $oldPassword = $request->old_password;
        $currentPassword = Auth::user()->password;
        if (Hash::check($oldPassword, $currentPassword)) {
            $foo = 'bar';
        }

    }

}