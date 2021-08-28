<?php

declare(strict_types=1);

namespace App\Domain\Shared\Specification;

class StringMinimumLengthSpecification implements Specification
{
    public function __construct(private int $minLength)
    {
    }

    public function isSatisfiedBy($value): bool
    {
        $stringLength = strlen(trim($value));

        return $stringLength >= $this->minLength;
    }
}
