<?php

declare(strict_types=1);

namespace App\Notification\Application\Listener;

use App\Shipping\Domain\Model\Event\ShippingOrderCreatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendConfirmationEmail
{
    public function __invoke(ShippingOrderCreatedEvent $event): void
    {
        // Integration Logic: Call external email provider (e.g., SendGrid, AWS SES)
        // Here we simulate it.
        
        $emailContent = sprintf(
            "Hello! Your shipment order %s has been created successfully.", 
            $event->orderId
        );
        
        // In real world: $this->mailer->send($event->senderEmail, $emailContent);
        
        // Logging to stdout to verify behavior in dev
        // error_log("NOTIFICATION CONTEXT: Sending email to {$event->senderEmail}: $emailContent");
    }
}
