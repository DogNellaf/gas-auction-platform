<?php

namespace Database\Factories;

use App\Enums\AuctionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuctionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'start_price' => fake()->randomFloat(2, 1000, 100000),
            'end_at'      => fake()->dateTimeBetween('+1 day', '+30 days'),
            'price_step'  => fake()->numberBetween(1, 20),
            'description' => fake()->paragraph(),
            'status'      => AuctionStatus::Opened,
        ];
    }

    public function hidden(): static
    {
        return $this->state(fn(array $attr) => ['status' => AuctionStatus::Hidden]);
    }

    public function closed(): static
    {
        return $this->state(fn(array $attr) => ['status' => AuctionStatus::Closed]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attr) => [
            'end_at' => now()->subHour(),
            'status' => AuctionStatus::Opened,
        ]);
    }
}
