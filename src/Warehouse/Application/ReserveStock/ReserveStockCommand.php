<?php

declare(strict_types=1);

namespace App\Warehouse\Application\ReserveStock;

readonly class ReserveStockCommand
{
    public function __construct(
        public string $sku,
        public int $quantity
    ) {}
}
