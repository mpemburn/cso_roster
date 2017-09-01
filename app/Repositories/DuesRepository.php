<?php
namespace App\Repositories;

use App\Contracts\Repositories\DuesRepositoryContract;

/**
 * Class DuesRepository
 * @package App\Repositories
 */
class DuesRepository extends AbstractRepository implements DuesRepositoryContract
{
    public function show($id = 0)
    {
        $thisDuesPayment = $this->model->findOrNew($id);

        $response = [
            'success' => $thisDuesPayment->exists,
            'data' => $thisDuesPayment->toArray()
        ];

        return response()->json($response);
    }
}