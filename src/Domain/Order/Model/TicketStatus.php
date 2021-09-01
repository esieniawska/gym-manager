<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Enum;

/**
 * @method static TicketStatus ACTIVE()
 * @method static TicketStatus NOT_ACTIVE()
 * @method static TicketStatus fromString(string $value)
 */
class TicketStatus extends Enum
{
    public const ACTIVE = 'ACTIVE';
    public const NOT_ACTIVE = 'NOT_ACTIVE';

    protected static function throwExceptionForInvalidValue(string $value): void
    {
        throw new InvalidValueException("Invalid status: $value");
    }
}
