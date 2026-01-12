<?php

declare(strict_types=1);

namespace App\Shipping\Domain\Model;

enum ShippingStatus: string
{
    case PENDING = 'pending';
    case PROCESSED = 'processed';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function isFinalized(): bool
    {
        return match($this) {
            self::DELIVERED, self::CANCELLED => true,
            default => false,
        };
    }
}
