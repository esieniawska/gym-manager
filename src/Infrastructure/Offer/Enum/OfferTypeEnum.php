<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Enum;

use App\Domain\Shared\ValueObject\Enum;
use App\Infrastructure\Offer\Exception\InvalidOfferTypeException;

/**
 * @method static OfferTypeEnum TYPE_NUMBER_OF_ENTRIES()
 * @method static OfferTypeEnum TYPE_NUMBER_OF_DAYS()
 * @method static OfferTypeEnum fromString(string $value)
 */
class OfferTypeEnum extends Enum
{
    public const TYPE_NUMBER_OF_ENTRIES = 'TYPE_NUMBER_OF_ENTRIES';
    public const TYPE_NUMBER_OF_DAYS = 'TYPE_NUMBER_OF_DAYS';

    protected static function throwExceptionForInvalidValue(string $value): void
    {
        throw new InvalidOfferTypeException('Invalid offer type');
    }
}
