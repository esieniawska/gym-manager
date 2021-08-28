<?php

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\StringHasValidPatternSpecification;

class Uuid extends StringValueObject
{
    private const VALID_PATTERN = '/\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-'.'[1-5]{1}[0-9A-Fa-f]{3}-[ABab89]{1}[0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}\z/';

    public function __construct(string $value)
    {
        $this->ensureIsSatisfiedValue($value, $this->getValidators());

        parent::__construct($value);
    }

    private function getValidators(): array
    {
        return [
            new SpecificationValidator(
                new StringHasValidPatternSpecification(self::VALID_PATTERN),
                'Invalid uuid.'
            ),
        ];
    }
}
