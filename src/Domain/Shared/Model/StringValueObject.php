<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

use App\Domain\Shared\Exception\StringIsToLongException;

class StringValueObject
{
    /**
     * @throws StringIsToLongException
     */
    public function __construct(protected string $value, int $maxLength = 255)
    {
        $this->value = trim($value);
        $this->validateValue($maxLength);
    }

    private function validateValue(int $maxLength)
    {
        if (strlen($this->value) > $maxLength) {
            throw new StringIsToLongException(sprintf('%s has more than %s characters', $this->value, $maxLength));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
