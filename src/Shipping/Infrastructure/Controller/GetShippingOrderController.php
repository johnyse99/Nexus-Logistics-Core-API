<?php

declare(strict_types=1);

namespace App\Shipping\Infrastructure\Controller;

use App\Shared\Domain\Bus\Query\QueryBusInterface;
use App\Shipping\Application\GetOrder\GetShippingOrderQuery;
use App\Shipping\Application\GetOrder\GetShippingOrderResponse;
use App\Shipping\Domain\Exception\ShippingOrderNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/v1/shipping', name: 'api_shipping_')]
final readonly class GetShippingOrderController
{
    public function __construct(
        private QueryBusInterface $queryBus
    ) {}

    #[Route('/orders/{id}', name: 'get_order', methods: ['GET'])]
    #[OA\Get(
        path: '/api/v1/shipping/orders/{id}',
        summary: 'Retrieves a shipping order by ID',
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
                response: 200,
                description: 'Order details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'string', example: '550e8400-e29b-41d4-a716-446655440000'),
                        new OA\Property(property: 'originAddress', type: 'string', example: 'Madrid, Spain'),
                        new OA\Property(property: 'destinationAddress', type: 'string', example: 'Paris, France'),
                        new OA\Property(property: 'weightInKg', type: 'number', format: 'float', example: 10.5),
                        new OA\Property(property: 'senderEmail', type: 'string', format: 'email', example: 'logistics@nexus.com'),
                        new OA\Property(property: 'status', type: 'string', example: 'pending'),
                        new OA\Property(property: 'createdAt', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'carrier', type: 'string', nullable: true, example: 'DHL')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Order not found'
            )
        ]
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            /** @var GetShippingOrderResponse $response */
            $response = $this->queryBus->ask(new GetShippingOrderQuery($id));

            return new JsonResponse([
                'id' => $response->id,
                'originAddress' => $response->origin,
                'destinationAddress' => $response->destination,
                'weightInKg' => $response->weight,
                'senderEmail' => $response->senderEmail,
                'status' => $response->status,
                'createdAt' => $response->createdAt,
                'carrier' => $response->carrier
            ]);
        } catch (ShippingOrderNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
