<?php

declare(strict_types=1);

namespace App\Shipping\Infrastructure\Persistence\Doctrine\Type;

use App\Shipping\Domain\Model\ShippingId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class ShippingIdType extends StringType
{
    public const NAME = 'shipping_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ShippingId
    {
        return $value !== null ? new ShippingId($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof ShippingId ? $value->value() : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
