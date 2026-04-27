<?php

namespace App\Enums;

/**
 * Senior note: Using Enums for roles ensures we don't use hardcoded strings in policies and middleware.
 */
enum UserRole: string
{
    case CUSTOMER = 'customer';
    case ADMIN = 'admin';

    /**
     * Helper to check if the role is admin.
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Helper to get a human-readable label.
     */
    public function label(): string
    {
        return match($this) {
            self::CUSTOMER => 'Klant',
            self::ADMIN => 'Administrator',
        };
    }
}
