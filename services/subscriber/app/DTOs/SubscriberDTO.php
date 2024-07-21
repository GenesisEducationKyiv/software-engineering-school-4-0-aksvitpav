<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;
use InvalidArgumentException;

readonly class SubscriberDTO
{
    /**
     * @param string $email
     * @param bool $isActive
     * @param Carbon|null $emailedAt
     * @param int|null $id
     */
    public function __construct(
        private string $email,
        private bool $isActive = true,
        private ?Carbon $emailedAt = null,
        private ?int $id = null,
    ) {
    }

    /**
     * @param array{
     *     "email":string,
     *     "is_active"?:bool,
     *      "emailed_at"?:Carbon,
     *      "id"?:int
     * } $data
     * @return SubscriberDTO
     */
    public static function fromArray(array $data): SubscriberDTO
    {
        if (!array_key_exists('email', $data)) {
            throw new InvalidArgumentException("Missing required key: email");
        }

        return new SubscriberDTO(
            email: (string)$data['email'],
            isActive: (bool)($data['is_active'] ?? true),
            emailedAt: ($data['emailed_at'] ?? null) ? Carbon::parse($data['emailed_at']) : null,
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
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
     * @return array{"email":string, "is_active":bool, "emailed_at": ?Carbon, "id": ?int}
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'is_active' => $this->isActive,
            'emailed_at' => $this->emailedAt,
            'id' => $this->id,
        ];
    }
}
