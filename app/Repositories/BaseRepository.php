<?php

namespace App\Repositories;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    /**
     *
     * @var Builder|Model|null
     */
    protected $model = null;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function builder()
    {
        return DB::table($this->model->getTable());
    }

    /**
     * @return Model|null
     */
    protected function makeModel()
    {
        if (is_null($this->model)) {
            $this->model = app($this->model());
        }
        return $this->model;
    }

    /**
     * @param string|null $connection
     * @return ConnectionInterface
     */
    public function getDBConnection(string $connection = null): ConnectionInterface
    {
        return DB::connection($connection ?? config("database.default"));
    }

    /**
     * @return string
     */
    protected function getPrimaryKeyName(): string
    {
        try {
            $key = $this->model->getKeyName();
        } catch (\Exception $e) {
            $key = 'id';
        }
        return $key;
    }

    /**
     * @param array|string[] $columns
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function insertGetId(array $data)
    {
        return $this->model->insertGetId($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function insertOrIgnore(array $data)
    {
        return $this->model->insertOrIgnore($data);
    }

    /**
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        return $this->model->where($this->getPrimaryKeyName(), $id)->update($attributes);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * @param array $search
     * @param array $create
     * @return object
     */
    public function firstOrCreate(array $search, array $create = []): object
    {
        return $this->model->firstOrCreate($search, $create);
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
     * @param $id
     * @return mixed
     */
    public function findWithTrashed($id)
    {
        if (in_array(SoftDeletes::class, class_uses($this->model)))
            return $this->model->withTrashed()->find($id);
        return $this->find($id);
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public function findByIds(array $ids): Collection
    {
        return $this->model
            ->newQuery()
            ->whereIn($this->getPrimaryKeyName(), $ids)
            ->get();
    }

    /**
     * @param array $ids
     * @return bool|null
     * @throws \Exception
     */
    public function delete(array $ids)
    {
        return $this->model->whereIn($this->getPrimaryKeyName(), $ids)->delete();
    }
}
