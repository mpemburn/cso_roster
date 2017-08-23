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
    public function findAll(array $where = [], array $with = [], int $limit = 10, array $belongsToArray = [])
    {
        $result = $this->model->with($with);

/* TODO: Implement $belongsTo logic

         foreach ($belongsToArray as $parentModel) {
            $parentModelPluralFunction = $this->getRelationshipFunctionName($parentModel, $this->model);

            $parentRelationship = $this->model->$parentModelPluralFunction();

            switch (true) {
                case ($parentRelationship instanceof BelongsTo):
                    $queryKey = $parentRelationship->getQualifiedForeignKey();
                    $parentModelKeyField = $parentRelationship->getOwnerKey();

                    break;

                case ($parentRelationship instanceof BelongsToMany):
                    $queryKey = $parentRelationship->getQualifiedRelatedKeyName();
                    $parentModelKeyField = $parentRelationship->getRelated()->getKeyName();
                    break;

                default:
                    throw new NotImplementedException('A relationship has not yet been handled.');
            }

            $parentModelValue = $parentModel->$parentModelKeyField;
            $result->whereHas($parentModelPluralFunction, function($query) use ($queryKey, $parentModelValue) {
                $query->where($queryKey, '=', $parentModelValue);
            });
        }
*/

        return $result->where($where)->paginate($limit)->appends(Input::except('page'));
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

}