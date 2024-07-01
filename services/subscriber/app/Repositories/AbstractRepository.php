<?php

namespace App\Repositories;

use App\Interfaces\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements RepositoryInterface
{
    /** @inheritDoc */
    public function getQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }

    /** @inheritDoc */
    abstract public function getModel(): Model;

    /** @inheritDoc */
    public function updateBy(array $conditions, array $updates): void
    {
        $models = $this->getQuery()->where($conditions)->get();

        foreach ($models as $model) {
            $model->update($updates);
        }
    }

    /** @inheritDoc */
    public function updateOrCreate(array $conditions, array $data): ?Model
    {
        return $this->getQuery()->updateOrCreate($conditions, $data);
    }

    /** @inheritDoc */
    public function insert(array $inserts): void
    {
        $this->getQuery()->insert($inserts);
    }

    /** @inheritDoc */
    public function upsert(array $data, array $uniqueBy, ?array $update = null): void
    {
        $this->getQuery()->upsert($data, $uniqueBy, $update);
    }

    /** @inheritDoc */
    public function insertGetId(array $inserts): int
    {
        return $this->getQuery()->insertGetId($inserts);
    }

    /** @inheritDoc */
    public function create(array $data): Model
    {
        return $this->getQuery()->create($data);
    }

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

        if (! empty($with)) {
            $query->with($with);
        }

        if (! empty($where)) {
            $query->where($where);
        }

        $query
            ->select($select);

        return $query->get();
    }

    /** @inheritDoc */
    public function delete(array $ids): void
    {
        $models = $this->getQuery()->whereIn('id', $ids)->get();

        foreach ($models as $model) {
            $model->delete();
        }
    }

    /** @inheritDoc */
    public function deleteById(int $id): void
    {
        $model = $this->getQuery()->where('id', $id)->first();
        $model?->delete();
    }

    /** @inheritDoc */
    public function deleteBy(array $conditions): void
    {
        $models = $this->getQuery()->where($conditions)->get();

        foreach ($models as $model) {
            $model->delete();
        }
    }

    /** @inheritDoc */
    public function updateById(int $id, array $values): Model
    {
        $model = $this->getById($id);
        $model->update($values);

        return $model;
    }

    /** @inheritDoc */
    public function paginateAll(
        ?int $page,
        ?int $perPage,
        array $with = [],
        array $select = ['*']
    ): LengthAwarePaginator {
        $query = $this->getQuery()->select($select);

        if ($with) {
            $query->with($with);
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /** @inheritDoc */
    public function deleteRelation(Model $model, string $relationName): void
    {
        $model->$relationName()->delete();
    }

    /** @inheritDoc */
    public function updateOrCreateRelation(Model $model, string $relationName, array $conditions, array $data): Model
    {
        return $model->$relationName()->updateOrCreate($conditions, $data);
    }

    /** @inheritDoc */
    public function createRelation(Model $model, string $relationName, array $data): Model
    {
        return $model->$relationName()->create($data);
    }

    /** @inheritDoc */
    public function updateRelation(Model $model, string $relationName, array $conditions, array $data): int
    {
        return $model->$relationName()->where($conditions)->update($data);
    }

    /** @inheritDoc */
    public function deleteNotExistingRelations(Model $model, string $relationName, array $ids): void
    {
        $query = $model->$relationName();

        if (!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }

        $models = $query->get();

        foreach ($models as $model) {
            $model->delete();
        }
    }

    /** @inheritDoc */
    public function syncRelation(Model $model, string $relationName, array $data): void
    {
        $model->$relationName()->sync($data);
    }
}
