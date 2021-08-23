<?php

namespace App\Domain\User\Specification;

interface Specification
{
    public function isSatisfiedBy(string $value): bool;
}
