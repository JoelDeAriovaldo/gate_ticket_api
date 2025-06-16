<?php

namespace App\Enums;

/**
 * Enum UserRole
 *
 * Defines the available user roles in the system.
 * Used for type-safe role management.
 */
enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * Get a human-readable label for the role.
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'User',
        };
    }
}
