<?php

declare(strict_types=1);

namespace App\Shipping\Infrastructure\Controller;

use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shipping\Application\CancelOrder\CancelShippingOrderCommand;
use App\Shipping\Domain\Exception\OrderAlreadyProcessedException;
use App\Shipping\Domain\Exception\ShippingOrderNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/v1/shipping', name: 'api_shipping_')]
final readonly class CancelShippingOrderController
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    #[Route('/orders/{id}/cancel', name: 'cancel_order', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/shipping/orders/{id}/cancel',
        summary: 'Cancels a shipping order',
        tags: ['Shipping'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'The order UUID',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            )
        ],
        responses: [
            new OA\Response(
                response: 202,
                description: 'Order cancelled successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Order not found'
            ),
            new OA\Response(
                response: 409,
                description: 'Order already processed/cancelled'
            )
        ]
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            $this->commandBus->dispatch(new CancelShippingOrderCommand($id));

            return new JsonResponse(['status' => 'cancelled'], Response::HTTP_ACCEPTED);
        } catch (ShippingOrderNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (OrderAlreadyProcessedException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
        }
    }
}
