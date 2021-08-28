<?php

namespace App\Domain\Shared\Specification;

interface Specification
{
    public function isSatisfiedBy(string|array|int|float $value): bool;
}
