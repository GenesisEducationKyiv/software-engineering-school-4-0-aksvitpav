<?php

namespace App\Repositories;

use App\Interfaces\Repositories\FindRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract readonly class AbstractFindRepository extends AbstractBaseRepository implements FindRepositoryInterface
{
    /** @inheritDoc */
    public function getById(int $id, ?array $with = null, ?array $select = ['*']): Model
    {
        $query = $this->getQuery()->select($select)->where('id', $id);

        if ($with) {
            $query->with($with);
        }

        return $query->firstOrFail();
    }

    /** @inheritDoc */
    public function getByIdWithoutRelations(int $id, array $where = [], array $select = ['*']): Model
    {
        $query = $this->getQuery()
            ->withoutEagerLoads()
            ->select($select)
            ->where('id', $id)
            ->where($where);

        return $query->firstOrFail();
    }

    /** @inheritDoc */
    public function getByIds(array $ids, ?array $with = [], ?array $select = ['*']): Collection
    {
        $query = $this->getQuery()->select($select)->whereIn('id', $ids);

        if ($with) {
            $query->with($with);
        }

        return $query->get();
    }

    /** @inheritDoc */
    public function findBy(
        array $conditions,
        string $orderBy = 'created_at',
        bool $orderAsc = true,
        ?array $with = null,
        ?array $select = ['*']
    ): ?Model {
        $query = $this->getQuery()->select($select)->where($conditions);

        if ($with) {
            $query->with($with);
        }

        if ($orderAsc) {
            $query->orderBy($orderBy);
        } else {
            $query->orderBy($orderBy, 'desc');
        }

        return $query->first();
    }

    /** @inheritDoc */
    public function exists(array $conditions): bool
    {
        return $this->getQuery()->where($conditions)->exists();
    }

    /** @inheritDoc */
    public function getAll(?array $select = ['*'], ?array $with = [], ?array $where = []): Collection
    {
        $query = $this->getQuery();

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($where)) {
            $query->where($where);
        }

        $query
            ->select($select);

        return $query->get();
    }
}
