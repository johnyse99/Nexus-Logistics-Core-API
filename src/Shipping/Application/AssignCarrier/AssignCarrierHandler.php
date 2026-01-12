<?php

declare(strict_types=1);

namespace App\Shipping\Application\AssignCarrier;

use App\Shipping\Domain\Exception\ShippingOrderNotFoundException;
use App\Shipping\Domain\Repository\ShippingOrderRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class AssignCarrierHandler
{
    public function __construct(
        private ShippingOrderRepositoryInterface $repository
    ) {}

    public function __invoke(AssignCarrierCommand $command): void
    {
        $order = $this->repository->search($command->requestId);

        if (null === $order) {
            throw ShippingOrderNotFoundException::withId($command->requestId);
        }

        $order->assignCarrier($command->carrierName);

        $this->repository->save($order);
    }
}
