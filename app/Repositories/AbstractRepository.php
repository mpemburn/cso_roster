<?php
namespace App\Repositories;

use App\Exceptions\NotImplementedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Input;

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
     * @param int $limit
     * @param array $belongsToArray
     * @return mixed
     * @throws NotImplementedException
     */
    public function findAll(array $where = [], array $with = [], int $limit = 10, array $orderBy = [])
    {
        $result = $this->model->with($with);
        $dataSet = $result->where($where)
            // Conditionally use $orderBy if not empty
            ->when(!empty($orderBy), function($query) use($orderBy) {
                $query->orderBy(...$orderBy);
            })
            //->orderBy(...$orderBy)
            ->paginate($limit)
            ->appends(Input::except('page'));

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

    // TODO: Figure out how to use multiple ORDER BY conditions
    protected function expandOrderBy($query, $orderByArray)
    {

    }
}