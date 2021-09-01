<?php

declare(strict_types=1);

namespace App\Domain\Shared\Specification;

class IntMinimumValueSpecification implements Specification
{
    public function __construct(private int $minValue)
    {
    }

    public function isSatisfiedBy(float|array|int|string $value): bool
    {
        return false !== filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => $this->minValue]]);
    }
}
