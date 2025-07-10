<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * get by id.
     * @param  int  $id
     * @return Model
     */
    public function getById($id)
    {
        return $this->model->find($id)->get();
    }

    /**
     * Get By Where.
     * @param  array  $where
     */
    public function getByWhere(string $where){
        return $this->model->where($where)->get();
    }

    /**
     * Create
     *
     * @param  string|array  $event
     * @return Model
     */
    public function create($event)
    {
        return $this->model->create($event);
    }

    /**
     * Update by id.
     *
     * @param  int    $id
     */
    public function updateById($id)
    {
        $result = $this->find($id);
        if ($result) {
            return $result->update($attributes);
        }
    }

    /**
     * Delete a record by id.
     *
     * @param  int  $id
     */
    public function deleteById($id)
    {
        $result = $this->find($id);
        if ($result) {
            return $result->delete();
        }
    }
}