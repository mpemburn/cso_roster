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
     * @param $email
     * @return mixed
     */
    function isValidMemberEmailAddress($email);
}