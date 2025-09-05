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
        return $this->model->where('id',$id)->first();
    }

    /**
     * Get By Where.
     * @param  array  $where
     */
    public function getByWhere(array $where)
    {
        return $this->model->where($where)->get();
    }

    /**
     * Get By first
     */
    public function getByFirst(array $where)
    {
        return $this->model->where($where)->first();
    }
    
    /**
     * get between where.
     */
    public function getBetweenWhere(string $column, $start, $end, array $where)
    {
        return $this->model->whereBetween($column, [$start, $end])->where($where)->orderBy('id', 'DESC')->get();
    }

    /**
     * get betwwen
     */
    public function getBetween (string $column, $start, $end)
    {
        return $this->model->whereBetween($column, [$start, $end])->orderBy('id', 'DESC')->get();
    }

    /**
     * get by multiple where.
     */
    public function getByMultipleWhere(array $where)
    {
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
    public function updateById($id,array $value)
    {
        return $this->model->where('id',$id)->update($value);
    }

    /**
     * Update by where.
     */
    public function updateByWhere(array $where, array $value)
    {
        return $this->model->where($where)->update($value);
    }

    /**
     * Delete a record by id.
     *
     * @param  int  $id
     */
    public function deleteById($id)
    {
        $result = $this->model->find($id);
        if ($result) {
            return $result->delete();
        }
    }
}