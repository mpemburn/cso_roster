<?php
namespace App\Contracts\Repositories;


/**
 * Interface MemberRepositoryContract
 * @package App\Contracts
 */
interface MemberRepositoryContract extends RepositoryContract
{
    function getDetails($id);
}