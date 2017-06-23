<?php
/**
 * Base repository contract used by all repositories.
 */
namespace App\Contracts;

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
    public function findAll();

}