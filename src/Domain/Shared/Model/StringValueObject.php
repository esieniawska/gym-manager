<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

use App\Domain\Shared\Exception\StringIsToLongException;

abstract class StringValueObject
{
    /**
     * @throws StringIsToLongException
     */
    public function __construct(protected string $value, ?int $maxLength = null)
    {
        $this->value = trim($value);

        if (null !== $maxLength) {
            $this->validateValue($maxLength);
        }
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
