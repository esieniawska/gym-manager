<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Type;

use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\ValueObject\Enum;
use App\Infrastructure\Shared\Type\BaseEnumType;

class OfferStatusType extends BaseEnumType
{
    public const NAME = 'offer_status';

    protected function createFromString(string $value): Enum
    {
        return OfferStatus::fromString($value);
    }
}
