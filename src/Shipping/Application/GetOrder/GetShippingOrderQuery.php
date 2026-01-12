<?php

declare(strict_types=1);

namespace App\Shipping\Application\GetOrder;

use App\Shared\Domain\Bus\Query\QueryInterface;

final readonly class GetShippingOrderQuery implements QueryInterface
{
    public function __construct(
        public string $id
    ) {}
}
