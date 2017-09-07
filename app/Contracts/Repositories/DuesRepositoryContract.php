<?php
namespace App\Contracts\Repositories;

/**
 * Interface DuesRepositoryContract
 * @package App\Contracts
 */
interface DuesRepositoryContract extends RepositoryContract
{
    public function show($id);

    public function save($request, $id);

    public function delete($id);
}