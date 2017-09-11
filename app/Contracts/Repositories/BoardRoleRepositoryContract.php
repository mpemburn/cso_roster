<?php
namespace App\Contracts\Repositories;

/**
 * Interface BoardRoleRepositoryContract
 * @package App\Contracts
 */
interface BoardRoleRepositoryContract extends RepositoryContract
{
    public function show($id);

    public function save($request, $id);

    public function delete($id);
}