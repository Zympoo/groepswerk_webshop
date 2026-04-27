<?php

namespace App\Enums;

/**
 * Senior note: Backed enums are preferred for database consistency.
 */
enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    /**
     * Helper to get a human-readable label.
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'In afwachting',
            self::PAID => 'Betaald',
            self::SHIPPED => 'Verzonden',
            self::CANCELLED => 'Geannuleerd',
            self::REFUNDED => 'Terugbetaald',
        };
    }
}
