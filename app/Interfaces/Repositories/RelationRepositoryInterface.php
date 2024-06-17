<?php

namespace App\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RelationRepositoryInterface
{
    /**
     * @param Model $model
     * @param string $relationName
     * @return void
     */
    public function deleteRelation(Model $model, string $relationName): void;

    /**
     * @param Model $model
     * @param string $relationName
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $data
     * @return Model
     */
    public function updateOrCreateRelation(
        Model $model,
        string $relationName,
        array $conditions,
        array $data
    ): Model;

    /**
     * @param Model $model
     * @param string $relationName
     * @param array<string, mixed> $data
     * @return Model
     */
    public function createRelation(Model $model, string $relationName, array $data): Model;

    /**
     * @param Model $model
     * @param string $relationName
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $data
     * @return int
     */
    public function updateRelation(Model $model, string $relationName, array $conditions, array $data): int;

    /**
     * @param Model $model
     * @param string $relationName
     * @param array<int> $ids
     * @return void
     */
    public function deleteNotExistingRelations(
        Model $model,
        string $relationName,
        array $ids
    ): void;

    /**
     * @param Model $model
     * @param string $relationName
     * @param array<string, mixed> $data
     * @return void
     */
    public function syncRelation(Model $model, string $relationName, array $data): void;
}
