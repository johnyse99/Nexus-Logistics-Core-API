<?php

declare(strict_types=1);

namespace App\Shipping\Application\CancelOrder;

use App\Shipping\Domain\Exception\ShippingOrderNotFoundException;
use App\Shipping\Domain\Repository\ShippingOrderRepositoryInterface;
use App\Shared\Domain\Bus\EventBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class CancelShippingOrderHandler
{
    public function __construct(
        private ShippingOrderRepositoryInterface $repository,
        private EventBusInterface $eventBus
    ) {}

    public function __invoke(CancelShippingOrderCommand $command): void
    {
        $order = $this->repository->search($command->requestId);

        if (null === $order) {
            throw ShippingOrderNotFoundException::withId($command->requestId);
        }

        $order->cancel();

        $this->repository->save($order);
        $this->eventBus->publish(...$order->pullDomainEvents());
    }
}
