<?php
namespace App\Contracts\Services;

use Illuminate\Http\Request;

/**
 * Interface MemberServiceContract
 * @package App\Contracts
 */
interface MemberServiceContract extends ServiceContract
{
    /**
     * @param $memberId
     * @return mixed
     */
    public function getMemberEmailFromId($memberId);

    /**
     * @param $email
     * @return mixed
     */
    public function getMemberFromEmail($email);

    /**
     * @param $user_id
     * @return mixed
     */
    public function getMemberFromUserId($user_id);

    /**
     * @param $user_id
     * @return mixed
     */
    public function getMemberIdFromUserId($user_id);

    /**
    * @param $token
    * @return Member
    */
    public function getMemberFromUserResetToken($token);

    /**
     * @param $token
     * @return string|bool
     */
    public function getMemberEmailFromUserResetToken($token);

    /**
     * @param $token
     * @return string|bool
     */
    public function getMemberIdFromUserResetToken($token);

    /**
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getUserFromMemberId($member_id);

    /**
     * @param $email
     * @return MemberService|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function getUserFromMemberEmailAddress($email);

    /**
     * @param $email
     * @return mixed
     */
    public function isValidMemberEmailAddress($email);

    /**
     * @param $request
     * @return mixed
     */
    public function resetUserPassword($request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function sendPasswordResetEmail(Request $request);

}