<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\StringHasValidPatternSpecification;

class Money extends ValueObject
{
    public const PATTERN = '/^[0-9]+(\.[0-9]{2})?$/';

    public function __construct(private float $value)
    {
        $this->ensureIsSatisfiedValue((string) $value, $this->getValidators());
    }

    public function getValue(): float
    {
        return $this->value;
    }

    private function getValidators(): array
    {
        return [
            new SpecificationValidator(
                new StringHasValidPatternSpecification(self::PATTERN),
                'Invalid price format.'
            ),
        ];
    }
}
