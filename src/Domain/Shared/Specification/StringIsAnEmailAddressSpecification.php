<?php

declare(strict_types=1);

namespace App\Domain\Shared\Specification;

class StringIsAnEmailAddressSpecification implements Specification
{
    public function isSatisfiedBy($value): bool
    {
        return is_string(filter_var($value, FILTER_VALIDATE_EMAIL));
    }
}
