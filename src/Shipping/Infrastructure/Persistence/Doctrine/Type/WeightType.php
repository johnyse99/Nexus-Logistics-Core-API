<?php

declare(strict_types=1);

namespace App\Shipping\Infrastructure\Persistence\Doctrine\Type;

use App\Shipping\Domain\Model\Weight;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DecimalType;

final class WeightType extends DecimalType
{
    public const NAME = 'weight';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Weight
    {
        return $value !== null ? new Weight((float) $value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?float
    {
        return $value instanceof Weight ? $value->value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
