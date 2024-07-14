<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerRepository extends AbstractRepository implements CustomerRepositoryInterface
{
    /** @inheritDoc */
    public function getModel(): Customer
    {
        return new Customer();
    }

    /**
     * @return Builder<Customer>
     */
    public function getQuery(): Builder
    {
        return Customer::query();
    }
}
