<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Type;

use App\Domain\Shared\ValueObject\Enum;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Shared\Type\BaseEnumType;

class OfferType extends BaseEnumType
{
    public const NAME = 'offer_type';

    protected function createFromString(string $value): Enum
    {
        return OfferTypeEnum::fromString($value);
    }
}
