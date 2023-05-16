<?php

namespace App\Services;

use App\Exceptions\EntityNotFoundException;
use App\Repositories\BaseRepository;
use Closure;
use Throwable;

abstract class BaseService
{
    /**
     * @var BaseRepository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     * @param BaseRepository $baseRepository
     */
    public function __construct(BaseRepository $baseRepository)
    {
        $this->repository = $baseRepository;
    }

    /**
     * @param Closure $callback
     * @param mixed|int $attempts
     * @return mixed
     * @throws Throwable
     */
    public function applyInTransaction(Closure $callback, int $attempts = 1)
    {
        $connection = $this->repository->getDBConnection();
        return $connection->transaction($callback, $attempts);
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ["*"])
    {
        return $this->repository->all($columns);
    }

    /**
     * @param $id
     * @param array $in
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function find($id, array $in = [])
    {
        return $this->findOrFail($id, $in);
    }

    /**
     * @param $id
     * @param array $in
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function findOrFail($id, array $in = [])
    {
        if (count($in) && !in_array($id, $in)) {
            throw new EntityNotFoundException();
        }

        if (!$entity = $this->repository->find($id)) {
            throw new EntityNotFoundException();
        }

        return $entity;
    }

    /**
     * @param mixed $data
     * @return mixed
     * @throws Throwable
     */
    public function add($data)
    {
        return $this->applyInTransaction(function () use ($data) {
            return $this->repository->add($data);
        });
    }

    /**
     * @param array $search
     * @param array $create
     * @return mixed
     * @throws Throwable
     */
    public function firstOrCreate(array $search, array $create = [])
    {
        return $this->applyInTransaction(function () use ($search, $create) {
            return $this->repository->firstOrCreate($search, $create);
        });
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    public function insert(array $data)
    {
        return $this->applyInTransaction(function () use ($data) {
            return $this->repository->insert($data);
        });
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws EntityNotFoundException|Throwable
     */
    public function update(int $id, array $data)
    {
        $this->findOrFail($id);
        return $this->applyInTransaction(function () use ($id, $data) {
            return $this->repository->update($id, $data);
        });
    }

    /**
     * @param array $ids
     * @return mixed
     * @throws Throwable
     */
    public function delete(array $ids)
    {
        return $this->applyInTransaction(function () use ($ids) {
            return $this->repository->delete($ids);
        });
    }
}
