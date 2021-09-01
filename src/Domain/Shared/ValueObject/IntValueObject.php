<?php

namespace App\Domain\Shared\ValueObject;

class IntValueObject extends ValueObject
{
    public function __construct(protected int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
