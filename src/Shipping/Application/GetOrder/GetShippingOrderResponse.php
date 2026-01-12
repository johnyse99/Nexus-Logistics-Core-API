<?php

declare(strict_types=1);

namespace App\Shipping\Application\GetOrder;

final readonly class GetShippingOrderResponse
{
    public function __construct(
        public string $id,
        public string $origin,
        public string $destination,
        public float $weight,
        public string $senderEmail,
        public string $status,
        public string $createdAt,
        public ?string $carrier
    ) {}
}
