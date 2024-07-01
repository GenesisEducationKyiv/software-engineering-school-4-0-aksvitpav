<?php

namespace App\Interfaces\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @return Model
     */
    public function getModel(): Model;

    /**
     * @return Builder<covariant Model>
     */
    public function getQuery(): Builder;

    /**
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $updates
     * @return void
     */
    public function updateBy(array $conditions, array $updates): void;

    /**
     * @param int $id
     * @param array<string, mixed> $values
     * @return Model
     */
    public function updateById(int $id, array $values): Model;

    /**
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $data
     * @return Model|null
     */
    public function updateOrCreate(array $conditions, array $data): ?Model;

    /**
     * @param array<string, mixed> $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param array<array<string, mixed>> $inserts
     * @return void
     */
    public function insert(array $inserts): void;

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $uniqueBy
     * @param array<string, mixed>|null $update
     * @return void
     */
    public function upsert(array $data, array $uniqueBy, ?array $update = null): void;

    /**
     * @param array<array<string, mixed>> $inserts
     * @return int
     */
    public function insertGetId(array $inserts): int;

    /**
     * @param int $id
     * @param array<string>|null $with
     * @param array<string>|null $select
     * @return Model
     */
    public function getById(int $id, ?array $with = [], ?array $select = ['*']): Model;

    /**
     * @param int $id
     * @param array<string, mixed> $where
     * @param array<string> $select
     * @return Model
     */
    public function getByIdWithoutRelations(int $id, array $where = [], array $select = ['*']): Model;

    /**
     * @param array<int> $ids
     * @param array<string>|null $with
     * @param array<string>|null $select
     * @return Collection<int, Model>
     */
    public function getByIds(array $ids, ?array $with = [], ?array $select = ['*']): Collection;

    /**
     * @param array<string, mixed> $conditions
     * @param string $orderBy
     * @param bool $orderAsc
     * @param array<string>|null $with
     * @param array<string>|null $select
     * @return Model|null
     */
    public function findBy(
        array $conditions,
        string $orderBy = 'created_at',
        bool $orderAsc = true,
        ?array $with = null,
        ?array $select = ['*']
    ): ?Model;

    /**
     * @param array<string, mixed> $conditions
     * @return bool
     */
    public function exists(array $conditions): bool;

    /**
     * @param array<string>|null $select
     * @param array<string>|null $with
     * @param array<string, mixed>|null $where
     * @return Collection<int, Model>
     */
    public function getAll(?array $select = ['*'], ?array $with = [], ?array $where = []): Collection;

    /**
     * @param array<int> $ids
     * @return void
     */
    public function delete(array $ids): void;

    /**
     * @param int $id
     * @return void
     */
    public function deleteById(int $id): void;

    /**
     * @param array<string, mixed> $conditions
     * @return void
     */
    public function deleteBy(array $conditions): void;

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @param array<string> $with
     * @param array<string> $select
     * @return LengthAwarePaginator<Model>
     */
    public function paginateAll(
        ?int $page,
        ?int $perPage,
        array $with = [],
        array $select = ['*']
    ): LengthAwarePaginator;

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
