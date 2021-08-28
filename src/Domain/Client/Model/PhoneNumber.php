<?php

declare(strict_types=1);

namespace App\Domain\Client\Model;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\StringHasValidPatternSpecification;
use App\Domain\Shared\ValueObject\StringValueObject;

class PhoneNumber extends StringValueObject
{
    public const PHONE_PATTERN = '/^[0-9]{9}$/';

    public function __construct(protected string $value)
    {
        $this->ensureIsSatisfiedValue($value, $this->getValidators());

        parent::__construct($value);
    }

    private function getValidators(): array
    {
        return [
            new SpecificationValidator(
                new StringHasValidPatternSpecification(self::PHONE_PATTERN),
                'Invalid phone number format.'
            ),
        ];
    }
}
