<?php

namespace App\Actions;

use App\DTOs\CustomerDTO;
use App\Interfaces\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class StoreCustomerAction
{
    /**
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
    ) {
    }

    /**
     * @param CustomerDTO $dto
     * @return Customer|Model
     */
    public function execute(CustomerDTO $dto): Customer|Model
    {
        return $this->customerRepository->create($dto->toArray());
    }
}
