<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

class PasswordMinLengthSpecification implements Specification
{
    public function __construct(private int $minLength)
    {
    }

    public function isSatisfiedBy(string $value): bool
    {
        return strlen($value) >= $this->minLength;
    }
}
