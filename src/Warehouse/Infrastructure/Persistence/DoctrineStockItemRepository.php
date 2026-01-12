<?php

declare(strict_types=1);

namespace App\Warehouse\Infrastructure\Persistence;

use App\Warehouse\Domain\Model\StockItem;
use App\Warehouse\Domain\Repository\StockItemRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineStockItemRepository implements StockItemRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(StockItem $stockItem): void
    {
        $this->entityManager->persist($stockItem);
        $this->entityManager->flush();
    }

    public function find(string $id): ?StockItem
    {
        return $this->entityManager->find(StockItem::class, $id);
    }
    
    public function findBySku(string $sku): ?StockItem
    {
        return $this->entityManager->getRepository(StockItem::class)->findOneBy(['sku' => $sku]);
    }
}
