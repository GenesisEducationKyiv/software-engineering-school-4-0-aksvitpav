<?php

namespace App\Repositories;

use App\Interfaces\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract readonly class AbstractBaseRepository implements BaseRepositoryInterface
{
    /** @inheritDoc */
    public function getQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }

    /** @inheritDoc */
    abstract public function getModel(): Model;
}
