<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->unique()->userName(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'company_name'      => fake()->unique()->company(),
            'form_id'           => 1,
            'phone'             => fake()->unique()->numerify('+7##########'),
            'is_approved'       => true,
            'is_admin'          => false,
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => ['email_verified_at' => null]);
    }

    public function unapproved(): static
    {
        return $this->state(fn(array $attributes) => ['is_approved' => false]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => ['is_admin' => true, 'is_approved' => true]);
    }
}
