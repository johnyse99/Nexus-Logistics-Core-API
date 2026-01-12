<?php

declare(strict_types=1);

namespace App\Shipping\Domain\Exception;

final class ShippingOrderNotFoundException extends \DomainException
{
    public static function withId(string $id): self
    {
        return new self(sprintf('Shipping order <%s> not found', $id));
    }
}
