<?php

declare(strict_types=1);

namespace App\Shipping\Application\AssignCarrier;

final readonly class AssignCarrierCommand
{
    public function __construct(
        public string $requestId,
        public string $carrierName
    ) {}
}
