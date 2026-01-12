<?php

declare(strict_types=1);

namespace App\Audit\Application\Listener;

use App\Audit\Domain\Model\AuditLog;
use App\Shipping\Domain\Model\Event\ShippingOrderCreatedEvent;
use App\Shipping\Domain\Model\Event\ShippingOrderCancelledEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final readonly class AuditShippingEventsHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[AsMessageHandler]
    public function onCreated(ShippingOrderCreatedEvent $event): void
    {
        $log = AuditLog::create(
            eventName: 'ShippingOrderCreated',
            payload: [
                'orderId' => $event->orderId,
                'sender' => $event->senderEmail,
                'timestamp' => $event->occurredOn->format(\DateTimeInterface::ATOM)
            ]
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    #[AsMessageHandler]
    public function onCancelled(ShippingOrderCancelledEvent $event): void
    {
        $log = AuditLog::create(
            eventName: 'ShippingOrderCancelled',
            payload: [
                'orderId' => $event->orderId,
                'timestamp' => $event->occurredOn->format(\DateTimeInterface::ATOM)
            ]
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
