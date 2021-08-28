<?php

declare(strict_types=1);

namespace App\Domain\Shared\Specification;

class ValuesAreBetweenAcceptedValuesSpecification implements Specification
{
    public function __construct(private array $correctValues)
    {
    }

    public function isSatisfiedBy($value): bool
    {
        $valuesOtherThanValid = array_diff($value, $this->correctValues);

        return empty($valuesOtherThanValid);
    }
}
