<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;

/**
 * UserSeeder
 *
 * Seeds the database with an initial admin user.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'username' => 'admin',
            'full_name' => 'Administrator',
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);
    }
}
