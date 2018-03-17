<?php
namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * @param array $data
     * @param Model $member
     * @return mixed
     */
    public function createNewContact(array $data = [], Model $member);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}