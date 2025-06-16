<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * UserService
 *
 * Handles user management business logic.
 * Used by UserController.
 */
class UserService
{
    /**
     * Get all users.
     *
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findUserById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update an existing user.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        $user->delete();
    }
}
