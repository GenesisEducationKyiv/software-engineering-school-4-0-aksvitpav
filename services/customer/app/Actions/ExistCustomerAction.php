<?php

namespace App\Actions;

use App\DTOs\CustomerDTO;
use App\Interfaces\Repositories\CustomerRepositoryInterface;

class ExistCustomerAction
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
     * @return bool
     */
    public function execute(CustomerDTO $dto): bool
    {
        return $this->customerRepository->exists(['email' => $dto->getEmail()]);
    }
}
