<?php

declare(strict_types=1);

namespace App\Shipping\Domain\Model;

readonly class Weight
{
    public function __construct(public float $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Weight must be positive.');
        }
        
        if ($value > 5000) {
            throw new \DomainException('Weight exceeds the industrial limit of 5000kg.');
        }
    }
}
