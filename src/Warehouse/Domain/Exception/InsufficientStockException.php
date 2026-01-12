<?php

declare(strict_types=1);

namespace App\Warehouse\Domain\Exception;

use DomainException;

final class InsufficientStockException extends DomainException
{
    public static function forItem(string $itemId, int $requested, int $available): self
    {
        return new self(sprintf(
            'Cannot reserve <%d> units for item <%s>. Only <%d> available.',
            $requested,
            $itemId,
            $available
        ));
    }
}
