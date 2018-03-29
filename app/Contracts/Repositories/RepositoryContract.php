<?php
/**
 * Base repository contract used by all repositories.
 */
namespace App\Contracts\Repositories;

/**
 * Interface RepositoryContract
 * @package App\Contracts
 */
interface RepositoryContract
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @return mixed
     */
    public function findAll(array $where = [], array $with = [], array $orderBy = [], int $limit = 10);

}