<?php
namespace App\Contracts\Repositories;

/**
 * Interface ContactRepositoryContract
 * @package App\Contracts
 */
interface ContactRepositoryContract extends RepositoryContract
{
    /**
     * @param $id
     * @return mixed
     */
    public function show($id);

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public function save($request, $id);
}