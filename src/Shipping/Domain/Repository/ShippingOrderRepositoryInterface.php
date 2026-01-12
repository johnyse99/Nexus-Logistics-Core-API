<?php

declare(strict_types=1);

namespace App\Shipping\Domain\Repository;

use App\Shipping\Domain\Model\ShippingOrder;

interface ShippingOrderRepositoryInterface
{
    public function save(ShippingOrder $order): void;
    
    public function search(string $id): ?ShippingOrder;
}
