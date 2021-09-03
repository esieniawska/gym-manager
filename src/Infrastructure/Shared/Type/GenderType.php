<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Type;

use App\Domain\Shared\ValueObject\Enum;
use App\Domain\Shared\ValueObject\Gender;

class GenderType extends BaseEnumType
{
    public const NAME = 'gender_type';

    protected function createFromString(string $value): Enum
    {
        return Gender::fromString($value);
    }
}
