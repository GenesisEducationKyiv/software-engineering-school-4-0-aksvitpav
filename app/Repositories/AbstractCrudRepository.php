<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract readonly class AbstractCrudRepository extends AbstractBaseRepository implements CrudRepositoryInterface
{
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
}
