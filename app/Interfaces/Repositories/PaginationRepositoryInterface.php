<?php

namespace App\Interfaces\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface PaginationRepositoryInterface extends CrudRepositoryInterface
{
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
}
