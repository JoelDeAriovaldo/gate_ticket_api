<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Enums\AccessGate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * TicketFactory
 *
 * Generates test data for tickets.
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $validUntil = $this->faker->dateTimeBetween('now', '+1 month');
        return [
            'truck_registration' => strtoupper($this->faker->bothify('??####')),
            'user_id' => User::factory(),
            'valid_until' => $validUntil,
            'access_gate' => $this->faker->randomElement(AccessGate::cases())->value,
            'status' => 'active',
            'used_at' => null,
        ];
    }
}
