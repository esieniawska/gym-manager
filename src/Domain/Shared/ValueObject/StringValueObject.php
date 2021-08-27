<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

abstract class StringValueObject
{
    public function __construct(protected string $value)
    {
        $this->value = trim($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
