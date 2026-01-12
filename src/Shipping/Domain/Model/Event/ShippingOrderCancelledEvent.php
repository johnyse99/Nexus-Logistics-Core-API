<?php

declare(strict_types=1);

namespace App\Shipping\Domain\Model\Event;

readonly class ShippingOrderCancelledEvent
{
    public function __construct(
        public string $orderId,
        public \DateTimeImmutable $occurredOn = new \DateTimeImmutable()
    ) {}
}
