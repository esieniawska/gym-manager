<?php

namespace App\Domain\Shared\Model;

use App\Domain\Shared\Exception\InvalidGenderException;

class Gender
{
    public const FEMALE = 'FEMALE';
    public const MALE = 'MALE';

    public function __construct(private string $gender)
    {
        $this->validateGender($gender);
    }

    private function validateGender(string $gender)
    {
        if (!in_array($gender, self::getGenders())) {
            throw new InvalidGenderException('Invalid gender');
        }
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public static function getGenders(): array
    {
        return [
            self::FEMALE,
            self::MALE,
        ];
    }
}
