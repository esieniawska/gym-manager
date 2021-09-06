<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Type;

use App\Domain\Shared\ValueObject\Enum;
use App\Infrastructure\Order\Enum\OrderTypeEnum;
use App\Infrastructure\Shared\Type\BaseEnumType;

class OrderType extends BaseEnumType
{
    public const NAME = 'order_type';

    protected function createFromString(string $value): Enum
    {
        return OrderTypeEnum::fromString($value);
    }
}
