<?php
namespace App\Services;

use App\Contracts\Services\MemberServiceContract;
use App\Models\Member;

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
     * @param $email
     * @return bool
     */
    public function isValidMemberEmailAddress($email)
    {
        $foundMember = $this->getMemberFromEmail($email);

        return (!is_null($foundMember));
    }

}