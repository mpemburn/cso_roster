<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * AbstractRepository constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param array $where
     * @param array $with
     * @param array $orderBy
     * @param int $limit
     * @return mixed
     */
    public function findAll(array $where = [], array $with = [], array $orderBy = [], $limit = null)
    {
        $result = $this->model->with($with);
        $dataSet = $result
            // Conditionally use $where if not empty
            ->when(!empty($where), function ($query) use ($where) {
                $this->chunkExpression($where, [$query, 'where']);
            })
            // Conditionally use $orderBy if not empty
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $this->chunkExpression($orderBy, [$query, 'orderBy']);
            })
            // Conditionally use $limit if not null
            ->when(!empty($limit), function ($query) use ($limit) {
                $query->limit($limit);
            })
            ->get();

        return $dataSet;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data = [])
    {
        $model = $this->model->newInstance($data);
        if (!$model->wasRecentlyCreated) {
            $model->save();
        }

        return $model;
    }

    /**
     * @param array $expression
     * @param array $queryMethod
     */
    protected function chunkExpression(array $expression, array $queryMethod)
    {
        // Break $expression into pairs
        $pairs = array_chunk($expression, 2);
        // Iterate over the pairs
        foreach ($pairs as $pair) {
            // Use the 'splat' to turn the pair into two arguments
            $queryMethod(...$pair);
        }

    }
}