<?php

declare(strict_types=1);

namespace App\Warehouse\Domain\Model;

readonly class InventoryLocation
{
    public function __construct(
        public string $aisle,
        public string $rack,
        public string $shelf
    ) {
        if (empty($aisle) || empty($rack) || empty($shelf)) {
            throw new \InvalidArgumentException('Location components cannot be empty.');
        }
    }

    public function __toString(): string
    {
        return sprintf('%s-%s-%s', $this->aisle, $this->rack, $this->shelf);
    }
}
