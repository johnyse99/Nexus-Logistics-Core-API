<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

abstract class AggregateRoot
{
    private array $domainEvents = [];

    protected function record(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function pullDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
