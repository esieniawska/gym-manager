<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

use App\Domain\Shared\Exception\InvalidGenderException;

class Gender extends StringValueObject
{
    public const FEMALE = 'FEMALE';
    public const MALE = 'MALE';

    public const GENDERS = [
        self::FEMALE,
        self::MALE,
    ];

    public function __construct(protected string $gender)
    {
        $this->validateGender($gender);
        parent::__construct($gender);
    }

    private function validateGender(string $gender): void
    {
        if (!in_array($gender, self::getGenders())) {
            throw new InvalidGenderException('Invalid gender');
        }
    }

    public static function getGenders(): array
    {
        return self::GENDERS;
    }
}
