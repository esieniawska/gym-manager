<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Enum;

use App\Domain\Shared\ValueObject\Enum;
use App\Infrastructure\Order\Exception\InvalidOrderTypeException;

/**
 * @method static OrderTypeEnum TYPE_NUMBER_OF_ENTRIES()
 * @method static OrderTypeEnum TYPE_NUMBER_OF_DAYS()
 * @method static OrderTypeEnum fromString(string $value)
 */
class OrderTypeEnum extends Enum
{
    public const TYPE_NUMBER_OF_ENTRIES = 'TYPE_NUMBER_OF_ENTRIES';
    public const TYPE_NUMBER_OF_DAYS = 'TYPE_NUMBER_OF_DAYS';

    protected static function throwExceptionForInvalidValue(string $value): void
    {
        throw new InvalidOrderTypeException('Invalid order type');
    }
}
