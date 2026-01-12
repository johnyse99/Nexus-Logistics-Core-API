<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus;

interface EventBusInterface
{
    public function publish(object ...$events): void;
    
    // Alias often used interchangeably or for specific dispatching logic
    public function dispatch(object $event): void;
}
