<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Enum;

/**
 * @method static OfferStatus ACTIVE()
 * @method static OfferStatus NOT_ACTIVE()
 * @method static OfferStatus fromString(string $value)
 */
class OfferStatus extends Enum
{
    public const ACTIVE = 'ACTIVE';
    public const NOT_ACTIVE = 'NOT_ACTIVE';

    public const ALL = [
        self::ACTIVE,
        self::NOT_ACTIVE,
    ];

    protected static function throwExceptionForInvalidValue(string $value): void
    {
        throw new InvalidValueException("Invalid status: $value");
    }
}
