<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Enum;

/**
 * @method static BuyerStatus ACTIVE()
 * @method static BuyerStatus NOT_ACTIVE()
 * @method static BuyerStatus fromString(string $value)
 */
class BuyerStatus extends Enum
{
    public const ACTIVE = 'ACTIVE';
    public const NOT_ACTIVE = 'NOT_ACTIVE';

    protected static function throwExceptionForInvalidValue(string $value): void
    {
        throw new InvalidValueException("Invalid status: $value");
    }
}
