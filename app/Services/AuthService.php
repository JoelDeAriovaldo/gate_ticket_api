<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * AuthService
 *
 * Handles authentication business logic.
 * Used by AuthController.
 */
class AuthService
{
    /**
     * Attempt to authenticate a user.
     *
     * @param string $username
     * @param string $password
     * @return User
     * @throws ValidationException
     */
    public function authenticate(string $username, string $password): User
    {
        $user = User::where('username', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'username' => ['User account is inactive.'],
            ]);
        }

        return $user;
    }
}
