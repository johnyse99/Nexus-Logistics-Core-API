<?php

declare(strict_types=1);

namespace App\Warehouse\Domain\Exception;

use DomainException;

final class StockConcurrencyException extends DomainException
{
    public static function versionMismatch(string $itemId, int $currentVersion): self
    {
        return new self(sprintf(
            'Optimistic locking failure: The stock item <%s> was modified by another transaction (Version: %d). Please retry.',
            $itemId,
            $currentVersion
        ));
    }
}
