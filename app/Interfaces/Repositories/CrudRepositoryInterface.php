<?php

namespace App\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Model;

interface CrudRepositoryInterface
{
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
}
