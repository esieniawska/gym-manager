<?php

declare(strict_types=1);

namespace App\Domain\Client\Model;

use App\Domain\Client\Exception\InvalidPhoneNumberException;
use App\Domain\Shared\ValueObject\StringValueObject;

class PhoneNumber extends StringValueObject
{
    public const PHONE_PATTERN = '/^[0-9]{9}$/';

    public function __construct(protected string $value)
    {
        $this->checkIsValidPhoneNumber($value);
        parent::__construct($value);
    }

    private function checkIsValidPhoneNumber(string $value): void
    {
        if (1 !== preg_match(self::PHONE_PATTERN, $value)) {
            throw new InvalidPhoneNumberException('Invalid phone number');
        }
    }
}
