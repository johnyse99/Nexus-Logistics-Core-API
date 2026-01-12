<?php

declare(strict_types=1);

namespace App\Audit\Domain\Model;

use Symfony\Component\Uid\Uuid;

readonly class AuditLog
{
    public function __construct(
        public string $id,
        public string $eventName,
        public array $payload,
        public \DateTimeImmutable $occurredOn
    ) {}

    public static function create(string $eventName, array $payload): self
    {
        return new self(
            Uuid::v7()->toRfc4122(),
            $eventName,
            $payload,
            new \DateTimeImmutable()
        );
    }
}
