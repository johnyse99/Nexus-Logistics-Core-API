<?php

declare(strict_types=1);

namespace App\Shipping\Domain\Model;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shipping\Domain\Model\Event\ShippingOrderCreatedEvent;
use App\Shipping\Domain\Exception\OrderAlreadyProcessedException;

final class ShippingOrder extends AggregateRoot
{
    private function __construct(
        private readonly ShippingId $id,
        private string $origin,
        private string $destination,
        private Weight $weight,
        private string $senderEmail,
        private ShippingStatus $status,
        private \DateTimeImmutable $createdAt,
        private ?string $carrier
    ) {}

    public static function create(
        ShippingId $id,
        string $origin,
        string $destination,
        Weight $weight,
        string $senderEmail
    ): self {
        
        if (str_contains(strtoupper($destination), 'ANTARCTICA')) {
            throw new \DomainException('Shipping to Antarctica is not supported yet.');
        }

        $order = new self(
            $id,
            $origin,
            $destination,
            $weight,
            $senderEmail,
            ShippingStatus::PENDING,
            new \DateTimeImmutable(),
            null
        );

        $order->record(new ShippingOrderCreatedEvent($id->value(), $senderEmail));

        return $order;
    }

    public function assignCarrier(string $carrierName): void
    {
        if ($this->status !== ShippingStatus::PENDING) {
             throw new \DomainException('Cannot assign carrier to a processed order.');
        }

        $this->carrier = $carrierName;
        $this->status = ShippingStatus::PROCESSED;
    }
    
    public function updateAddress(string $newAddress): void
    {
        if ($this->status->isFinalized()) {
            throw OrderAlreadyProcessedException::withId(
                $this->id->value(), 
                $this->status->value
            );
        }
        
        $this->destination = $newAddress;
    }

    public function cancel(): void
    {
        if ($this->status->isFinalized()) {
            throw OrderAlreadyProcessedException::withId(
                $this->id->value(),
                $this->status->value
            );
        }

        $this->status = ShippingStatus::CANCELLED;
        $this->record(new \App\Shipping\Domain\Model\Event\ShippingOrderCancelledEvent($this->id->value()));
    }

    public function id(): ShippingId { return $this->id; }
    public function origin(): string { return $this->origin; }
    public function destination(): string { return $this->destination; }
    public function weight(): Weight { return $this->weight; }
    public function senderEmail(): string { return $this->senderEmail; }
    public function status(): ShippingStatus { return $this->status; }
    public function createdAt(): \DateTimeImmutable { return $this->createdAt; }
    public function carrier(): ?string { return $this->carrier; }
}
