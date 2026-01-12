<?php

declare(strict_types=1);

namespace App\Shipping\Infrastructure\Controller;

use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shipping\Application\AssignCarrier\AssignCarrierCommand;
use App\Shipping\Domain\Exception\ShippingOrderNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/v1/shipping', name: 'api_shipping_')]
final readonly class AssignCarrierController
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    #[Route('/orders/{id}/assign', name: 'assign_carrier', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/shipping/orders/{id}/assign',
        summary: 'Assigns a carrier to a shipping order',
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
        requestBody: new OA\RequestBody(
            description: 'Carrier data',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'carrierName', type: 'string', example: 'DHL Express')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 202,
                description: 'Carrier assigned successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Order not found'
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input'
            )
        ]
    )]
    public function __invoke(Request $request, string $id): JsonResponse
    {
        $data = $request->toArray();
        
        if (!isset($data['carrierName'])) {
            return new JsonResponse(['error' => 'carrierName is required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->commandBus->dispatch(new AssignCarrierCommand($id, $data['carrierName']));

            return new JsonResponse(['status' => 'assigned'], Response::HTTP_ACCEPTED);
        } catch (ShippingOrderNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\DomainException $e) {
             return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
        }
    }
}
