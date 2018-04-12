<?php
namespace App\Contracts\Services;

use Illuminate\Http\Request;
use App\Contracts\Repositories\MemberRepositoryContract;

/**
 * Interface MemberServiceContract
 * @package App\Contracts
 */
interface MemberServiceContract extends ServiceContract
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function createMember(Request $request);

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
     * @return mixed
     */
    public function getUserFromMemberEmailAddress($email);

    /**
     * @param $email
     * @param $zip
     * @return mixed
     */
    public function getMemberFromEmailAndZip($email, $zip);

    /**
     * @param $phone
     * @return mixed
     */
    public function getMemberFromPhoneNumber($phone);

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