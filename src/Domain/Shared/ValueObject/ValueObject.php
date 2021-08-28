<?php

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\Specification\SpecificationValidator;

abstract class ValueObject
{
    /**
     * @param SpecificationValidator[] $validators
     *
     * @throws InvalidValueException
     */
    protected function ensureIsSatisfiedValue(string|array|int|float $value, array $validators): void
    {
        foreach ($validators as $validator) {
            $validator->checkIsValidSpecification($value);
        }
    }
}
