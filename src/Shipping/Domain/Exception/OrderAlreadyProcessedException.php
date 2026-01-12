<?php

declare(strict_types=1);

namespace App\Shipping\Domain\Exception;

use DomainException;

final class OrderAlreadyProcessedException extends DomainException
{
    public static function withId(string $orderId, string $currentStatus): self
    {
        return new self(sprintf(
            'The order <%s> cannot be modified because it is already in status <%s>.',
            $orderId,
            $currentStatus
        ));
    }
}
