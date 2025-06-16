<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * UserFactory
 *
 * Generates test data for users.
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName,
            'full_name' => $this->faker->name,
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement([UserRole::ADMIN, UserRole::USER]),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }
}
