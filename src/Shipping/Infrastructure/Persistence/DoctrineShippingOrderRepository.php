<?php

declare(strict_types=1);

namespace App\Shipping\Infrastructure\Persistence;

use App\Shipping\Domain\Model\ShippingOrder;
use App\Shipping\Domain\Model\ShippingId;
use App\Shipping\Domain\Repository\ShippingOrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineShippingOrderRepository implements ShippingOrderRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(ShippingOrder $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    public function search(string $id): ?ShippingOrder
    {
        return $this->entityManager->find(ShippingOrder::class, new ShippingId($id));
    }
}
