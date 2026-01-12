<?php

declare(strict_types=1);

namespace App\Shipping\Domain\Model\Event;

readonly class ShippingOrderCreatedEvent
{
    public function __construct(
        public string $orderId,
        public string $senderEmail, // Assuming this is available/needed based on prompt usage
        public \DateTimeImmutable $occurredOn = new \DateTimeImmutable()
    ) {}
}
