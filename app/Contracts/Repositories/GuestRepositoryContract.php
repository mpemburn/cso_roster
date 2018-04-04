<?php
namespace App\Contracts\Repositories;

use Illuminate\Http\Request;

/**
 * Interface GuestRepositoryContract
 * @package App\Contracts
 */
interface GuestRepositoryContract extends RepositoryContract
{
    public function show($id);

    public function delete($id);
}