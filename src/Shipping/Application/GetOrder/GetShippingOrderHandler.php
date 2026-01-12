<?php

declare(strict_types=1);

namespace App\Shipping\Application\GetOrder;

use App\Shipping\Domain\Repository\ShippingOrderRepositoryInterface;
use App\Shipping\Domain\Exception\ShippingOrderNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class GetShippingOrderHandler
{
    public function __construct(
        private ShippingOrderRepositoryInterface $repository
    ) {}

    public function __invoke(GetShippingOrderQuery $query): GetShippingOrderResponse
    {
        $order = $this->repository->search($query->id);

        if (null === $order) {
            throw ShippingOrderNotFoundException::withId($query->id);
        }

        return new GetShippingOrderResponse(
            $order->id()->value(),
            $order->origin(),
            $order->destination(),
            $order->weight()->value,
            $order->senderEmail(),
            $order->status()->value,
            $order->createdAt()->format(\DateTimeInterface::ATOM),
            $order->carrier()
        );
    }
}
