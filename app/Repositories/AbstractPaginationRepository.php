<?php

namespace App\Repositories;

use App\Interfaces\Repositories\PaginationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract readonly class AbstractPaginationRepository extends AbstractFindRepository implements
    PaginationRepositoryInterface
{
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
}
