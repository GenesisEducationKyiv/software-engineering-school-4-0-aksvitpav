<?php

namespace App\DTOs;

use InvalidArgumentException;

readonly class CustomerDTO
{
    /**
     * @param string $email
     * @param int|null $id
     */
    public function __construct(
        private string $email,
        private ?int $id = null,
    ) {
    }

    /**
     * @param array{
     *     "email":string,
     *      "id"?:int
     * } $data
     * @return CustomerDTO
     */
    public static function fromArray(array $data): CustomerDTO
    {
        if (!array_key_exists('email', $data)) {
            throw new InvalidArgumentException("Missing required key: email");
        }

        return new CustomerDTO(
            email: (string)$data['email'],
            id: ($data['id'] ?? null) ? (int)$data['id'] : null,
        );
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array{"email":string, "id": ?int}
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'id' => $this->id,
        ];
    }
}
