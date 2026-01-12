<?php

declare(strict_types=1);

namespace App\Shipping\Application\CancelOrder;

final readonly class CancelShippingOrderCommand
{
    public function __construct(
        public string $requestId
    ) {}
}
