<?php

namespace App\Repositories;

use App\Interfaces\Repositories\PaginationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract readonly class AbstractRelationBaseRepository extends AbstractPaginationRepository implements
    PaginationRepositoryInterface
{
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
