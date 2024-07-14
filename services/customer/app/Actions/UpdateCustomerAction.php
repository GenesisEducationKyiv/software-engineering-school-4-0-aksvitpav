<?php

namespace App\Actions;

use App\DTOs\CustomerDTO;
use App\Interfaces\Repositories\CustomerRepositoryInterface;

class UpdateCustomerAction
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
     * @return void
     */
    public function execute(CustomerDTO $dto): void
    {
        $this->customerRepository->updateBy(['email' => $dto->getEmail()], $dto->toArray());
    }
}
