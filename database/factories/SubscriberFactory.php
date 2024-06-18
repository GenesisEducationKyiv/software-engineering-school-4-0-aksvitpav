<?php

namespace Database\Factories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail,
            'emailed_at' => null,
        ];
    }

    public function emailedYesterday(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'emailed_at' => now()->subDay(),
            ];
        });
    }
}
