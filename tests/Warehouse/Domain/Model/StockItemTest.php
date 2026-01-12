<?php

declare(strict_types=1);

namespace App\Tests\Warehouse\Domain\Model;

use App\Warehouse\Domain\Exception\InsufficientStockException;
use App\Warehouse\Domain\Model\InventoryLocation;
use App\Warehouse\Domain\Model\StockId;
use App\Warehouse\Domain\Model\StockItem;
use PHPUnit\Framework\TestCase;

class StockItemTest extends TestCase
{
    /** @test */
    public function it_should_reserve_stock_successfully(): void
    {
        $item = StockItem::create(
            StockId::random(),
            'SKU-123',
            new InventoryLocation('A', '1', '10'),
            100
        );

        $item->reserve(10);

        $this->assertEquals(90, $item->quantityAvailable());
    }

    /** @test */
    public function it_should_throw_exception_on_insufficient_stock(): void
    {
        $item = StockItem::create(
            StockId::random(),
            'SKU-123',
            new InventoryLocation('A', '1', '10'),
            5
        );

        $this->expectException(InsufficientStockException::class);

        $item->reserve(10);
    }
}
