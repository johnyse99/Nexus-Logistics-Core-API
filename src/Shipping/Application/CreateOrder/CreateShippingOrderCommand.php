<?php

declare(strict_types=1);

namespace App\Shipping\Application\CreateOrder;

readonly class CreateShippingOrderCommand
{
    public function __construct(
        public string $requestId,      // Idempotency Key
        public string $originAddress,
        public string $destinationAddress,
        public float $weightInKg,
        public string $senderEmail
    ) {}
}
