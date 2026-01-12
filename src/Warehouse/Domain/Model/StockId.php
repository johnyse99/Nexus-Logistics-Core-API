<?php

declare(strict_types=1);

namespace App\Warehouse\Domain\Model;

use Symfony\Component\Uid\Uuid;

readonly class StockId
{
    public function __construct(public string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('<%s> is not a valid UUID.', $value));
        }
    }

    public static function random(): self
    {
        return new self(Uuid::v7()->toRfc4122());
    }

    public function equals(StockId $other): bool
    {
        return $this->value === $other->value;
    }
    
    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
