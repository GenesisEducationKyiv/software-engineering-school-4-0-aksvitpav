<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

readonly class SubscriberDTO
{
    /**
     * @param string $email
     * @param Carbon|null $emailedAt
     * @param int|null $id
     */
    public function __construct(
        private string $email,
        private ?Carbon $emailedAt = null,
        private ?int $id = null,
    ) {
    }

    /**
     * @param array{"email":string, "emailed_at": ?Carbon, "id": ?int} $data
     * @return SubscriberDTO
     */
    public static function fromArray(array $data): SubscriberDTO
    {
        return new SubscriberDTO(
            email: $data['email'] ?? null,
            emailedAt: $data['emailed_at'] ?? null,
            id: $data['id'] ?? null,
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
     * @return Carbon|null
     */
    public function getEmailedAt(): ?Carbon
    {
        return $this->emailedAt;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array{"email":string, "emailed_at": ?Carbon, "id": ?int}
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'emailed_at' => $this->emailedAt,
            'id' => $this->id,
        ];
    }
}
