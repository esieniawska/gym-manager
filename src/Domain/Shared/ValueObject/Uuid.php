<?php

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidUuidException;

class Uuid extends StringValueObject
{
    private const VALID_PATTERN = '/\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-'.'[1-5]{1}[0-9A-Fa-f]{3}-[ABab89]{1}[0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}\z/';

    public function __construct(string $value)
    {
        $this->validateUuid($value);

        parent::__construct($value);
    }

    private function validateUuid(string $uuid): void
    {
        if (1 !== preg_match(self::VALID_PATTERN, $uuid)) {
            throw new InvalidUuidException('Invalid uuid');
        }
    }
}
