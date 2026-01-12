<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Nexus Logistics Core API',
    description: 'API for managing logistics, shipping orders, and warehouses.',
    contact: new OA\Contact(
        email: 'api@nexus-logistics.com'
    )
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Local Dev Server'
)]
class OpenApiSpec
{
}
