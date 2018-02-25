<?php
namespace App\Contracts\Repositories;


/**
 * Interface MemberRepositoryContract
 * @package App\Contracts
 */
interface MemberRepositoryContract extends RepositoryContract
{
    /**
     * @param $id
     * @return mixed
     */
    function getDetails($id);

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    function save($request, $id, $rules);

    /**
     * @param $memberId
     * @return mixed
     */
    function retrieveContacts($memberId);

    /**
     * @param $memberId
     * @return mixed
     */
    function retrieveDues($memberId);

    /**
     * @param $memberId
     * @return mixed
     */
    function retrieveRoles($memberId);

}