<?php

declare(strict_types=1);

namespace App\Domain\Shared\Specification;

use App\Domain\Shared\Exception\InvalidValueException;

class SpecificationValidator
{
    public function __construct(private Specification $specification, private string $errorMessage)
    {
    }

    public function checkIsValidSpecification(string|array|int|float $value): void
    {
        if (!$this->specification->isSatisfiedBy($value)) {
            throw new InvalidValueException($this->errorMessage);
        }
    }
}
