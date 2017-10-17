<?php
namespace App\Contracts\Services;


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
    function getMemberEmailFromId($memberId);

    /**
     * @param $email
     * @return mixed
     */
    function getMemberFromEmail($email);

    /**
     * @param $user_id
     * @return mixed
     */
    function getMemberFromUserId($user_id);

    /**
     * @param $user_id
     * @return mixed
     */
    function getMemberIdFromUserId($user_id);

    /**
     * @param $email
     * @return mixed
     */
    function isValidMemberEmailAddress($email);

    /**
     * @param $request
     * @return mixed
     */
    function resetUserPassword($request);
}