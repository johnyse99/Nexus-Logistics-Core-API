<?php

declare(strict_types=1);

namespace App\Warehouse\Application\ReserveStock;

use App\Warehouse\Domain\Exception\InsufficientStockException;
use App\Warehouse\Domain\Exception\StockConcurrencyException;
use App\Warehouse\Domain\Repository\StockItemRepositoryInterface;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\OptimisticLockException;

final readonly class ReserveStockHandler
{
    public function __construct(
        private StockItemRepositoryInterface $repository
    ) {}

    public function __invoke(ReserveStockCommand $command): void
    {
        // 1. Load Aggregate
        $stockItem = $this->repository->findBySku($command->sku);

        if ($stockItem === null) {
            throw new \InvalidArgumentException(sprintf('Stock item with SKU <%s> not found.', $command->sku));
        }

        // 2. Business Logic
        try {
            $stockItem->reserve($command->quantity);
            
            // 3. Persistence with Optimistic Locking Check
            // The method save() typically flushes. If Doctrine detects a version mismatch,
            // it throws OptimisticLockException.
            $this->repository->save($stockItem);
            
        } catch (OptimisticLockException $e) {
            // Translate Infrastructure Exception to Domain Exception
            throw StockConcurrencyException::versionMismatch($command->sku, $stockItem->version());
        }
    }
}
