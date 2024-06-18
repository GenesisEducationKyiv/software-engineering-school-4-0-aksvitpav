<?php

namespace App\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface FindRepositoryInterface
{
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
}
