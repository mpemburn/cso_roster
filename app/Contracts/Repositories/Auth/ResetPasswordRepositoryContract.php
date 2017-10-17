<?php
namespace App\Contracts\Repositories\Auth;
use App\Contracts\Repositories\RepositoryContract;

/**
 * Interface ResetPasswordRepositoryContract
 * @package App\Contracts
 */
interface ResetPasswordRepositoryContract extends RepositoryContract
{
    public function save($request, $userId);
}