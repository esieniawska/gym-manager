<?php

declare(strict_types=1);

namespace App\Domain\Shared\Specification;

class StringHasValidPatternSpecification implements Specification
{
    public function __construct(private string $pattern)
    {
    }

    public function isSatisfiedBy($value): bool
    {
        return 1 === preg_match($this->pattern, $value);
    }
}
