<?php

namespace Database\Factories;

use App\Enums\BidStatus;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidFactory extends Factory
{
    protected $model = \App\Models\Bid::class;

    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'auction_id' => Auction::factory(),
            'price'      => fake()->randomFloat(2, 1000, 200000),
            'status'     => BidStatus::Waiting,
        ];
    }

    public function win(): static
    {
        return $this->state(fn(array $attr) => ['status' => BidStatus::Win]);
    }

    public function lose(): static
    {
        return $this->state(fn(array $attr) => ['status' => BidStatus::Lose]);
    }
}
