<?php

declare(strict_types=1);

namespace App\Warehouse\Domain\Repository;

use App\Warehouse\Domain\Model\StockItem;

interface StockItemRepositoryInterface
{
    public function save(StockItem $stockItem): void;
    
    // In a real app we might search by SKU, but ID is standard for Aggregate Root retrieval
    public function find(string $id): ?StockItem;
    
    public function findBySku(string $sku): ?StockItem;
}
