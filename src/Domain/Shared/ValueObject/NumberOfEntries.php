<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Specification\IntMinimumValueSpecification;
use App\Domain\Shared\Specification\SpecificationValidator;

class NumberOfEntries extends IntValueObject
{
    public const MIN_VALUE = 1;

    public function __construct(protected int $value)
    {
        $this->ensureIsSatisfiedValue($value, $this->getValidators());

        parent::__construct($value);
    }

    private function getValidators(): array
    {
        return [
            new SpecificationValidator(
                new IntMinimumValueSpecification(self::MIN_VALUE),
                sprintf('NumberOfEntries must be greater than %s.', self::MIN_VALUE)
            ),
        ];
    }
}
