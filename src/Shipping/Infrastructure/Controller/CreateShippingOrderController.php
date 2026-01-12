<?php

declare(strict_types=1);

namespace App\Shipping\Infrastructure\Controller;

use App\Shipping\Application\CreateOrder\CreateShippingOrderCommand;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/v1/shipping', name: 'api_shipping_')]
final readonly class CreateShippingOrderController
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    #[Route('/orders', name: 'create_order', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/shipping/orders',
        summary: 'Creates a new shipping order',
        tags: ['Shipping'],
        requestBody: new OA\RequestBody(
            description: 'Order data',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'requestId', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
                    new OA\Property(property: 'originAddress', type: 'string', example: 'Madrid, Spain'),
                    new OA\Property(property: 'destinationAddress', type: 'string', example: 'Paris, France'),
                    new OA\Property(property: 'weightInKg', type: 'number', format: 'float', example: 10.5),
                    new OA\Property(property: 'senderEmail', type: 'string', format: 'email', example: 'logistics@nexus.com')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 202,
                description: 'Order accepted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'accepted'),
                        new OA\Property(property: 'requestId', type: 'string', example: '550e8400-e29b-41d4-a716-446655440000')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input'
            )
        ]
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $command = new CreateShippingOrderCommand(
            $data['requestId'],
            $data['originAddress'],
            $data['destinationAddress'],
            (float) $data['weightInKg'],
            $data['senderEmail']
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse([
            'status' => 'accepted',
            'requestId' => $data['requestId']
        ], Response::HTTP_ACCEPTED);
    }
}
