<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\StringHasValidPatternSpecification;

class Money extends ValueObject
{
    public const PATTERN = '/^[0-9]+(\.[0-9]{2})?$/';

    private int $value;

    public function __construct(float|int $value)
    {
        if (is_float($value)) {
            $this->ensureIsSatisfiedValue((string) $value, $this->getValidators());
            $this->setFromFloatValue($value);
        } else {
            $this->setFromIntValue($value);
        }
    }

    public function getFloatValue(): float
    {
        return $this->value / 100;
    }

    public function getIntValue(): int
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

    private function setFromFloatValue(float $value): void
    {
        $this->value = (int) (100 * $value);
    }

    private function setFromIntValue(int $value): void
    {
        $this->value = $value;
    }
}
