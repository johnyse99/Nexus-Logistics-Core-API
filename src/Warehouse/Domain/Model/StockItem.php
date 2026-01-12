<?php

declare(strict_types=1);

namespace App\Warehouse\Domain\Model;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Warehouse\Domain\Exception\InsufficientStockException;

final class StockItem extends AggregateRoot
{
    private function __construct(
        private readonly StockId $id,
        private string $sku,
        private int $quantityAvailable,
        private InventoryLocation $location,
        private ?\DateTimeImmutable $updatedAt,
        private int $version // For Optimistic Locking
    ) {}

    public static function create(
        StockId $id,
        string $sku,
        InventoryLocation $location,
        int $initialQuantity = 0
    ): self {
        return new self(
            $id,
            $sku,
            max(0, $initialQuantity),
            $location,
            new \DateTimeImmutable(),
            1 // Initial version
        );
    }

    public function reserve(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Reservation quantity must be positive.');
        }

        if ($this->quantityAvailable < $quantity) {
            throw InsufficientStockException::forItem(
                $this->id->value(),
                $quantity,
                $this->quantityAvailable
            );
        }

        $this->quantityAvailable -= $quantity;
        $this->updatedAt = new \DateTimeImmutable();
        
        // In a real Doctrine setup, Doctrine increments the version automatically on flush.
        // However, for pure Domain Testing, we might want to manually bump it OR 
        // trust the infrastructure to handle it. 
        // Typically in DDD with Optimistic Locking, the 'version' is managed by the ORM,
        // but exposed in the domain to handle business logic if needed.
        // We won't manually increment here to avoid double increment issues with ORM,
        // unless we mapped it as a normal column, not `@Version`.
        // For this "Model", I will treat it as read-only state reflective of persistence.
    }
    
    public function addStock(int $quantity): void
    {
         if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity to add must be positive.');
        }
        
        $this->quantityAvailable += $quantity;
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getters
    public function id(): StockId { return $this->id; }
    public function sku(): string { return $this->sku; }
    public function quantityAvailable(): int { return $this->quantityAvailable; }
    public function location(): InventoryLocation { return $this->location; }
    public function version(): int { return $this->version; }
}
