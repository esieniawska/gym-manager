<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidGenderException;

/**
 * @method static Gender FEMALE()
 * @method static Gender MALE()
 * @method static Gender fromString(string $value)
 */
class Gender extends Enum
{
    public const FEMALE = 'FEMALE';
    public const MALE = 'MALE';

    public const ALL = [
        self::FEMALE,
        self::MALE,
    ];

    public function __construct(protected string $gender)
    {
        parent::__construct($gender);
    }

    protected static function throwExceptionForInvalidValue(string $value): void
    {
        throw new InvalidGenderException("Invalid gender: $value");
    }
}
