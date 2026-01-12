<?php

declare(strict_types=1);

namespace App\Shipping\Application\CreateOrder;

use App\Shipping\Domain\Model\ShippingId;
use App\Shipping\Domain\Model\ShippingOrder;
use App\Shipping\Domain\Model\Weight;
use App\Shipping\Domain\Repository\ShippingOrderRepositoryInterface;
use App\Shared\Domain\Bus\EventBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class CreateShippingOrderHandler
{
    public function __construct(
        private ShippingOrderRepositoryInterface $repository,
        private EventBusInterface $eventBus
    ) {}

    public function __invoke(CreateShippingOrderCommand $command): void
    {
        // 1. Idempotency Check
        $existingOrder = $this->repository->search($command->requestId);
        
        if ($existingOrder !== null) {
            return; 
        }

        // 2. Domain Logic
        $id = new ShippingId($command->requestId);
        $weight = new Weight($command->weightInKg);

        $shippingOrder = ShippingOrder::create(
            $id,
            $command->originAddress,
            $command->destinationAddress,
            $weight,
            $command->senderEmail
        );

        // 3. Persistence
        $this->repository->save($shippingOrder);

        // 4. Publish Events
        $this->eventBus->publish(...$shippingOrder->pullDomainEvents());
    }
}
