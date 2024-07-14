<?php

namespace App\Actions;

use App\Interfaces\Repositories\CustomerRepositoryInterface;

class DeleteCustomerAction
{
    /**
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
    ) {
    }

    /**
     * @param string $email
     * @return void
     */
    public function execute(string $email): void
    {
        $this->customerRepository->deleteBy(['email' => $email]);
    }
}
