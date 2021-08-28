<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\StringIsAnEmailAddressSpecification;

class EmailAddress extends StringValueObject
{
    public function __construct(protected string $value)
    {
        $this->ensureIsSatisfiedValue($value, $this->getValidators($this->value));

        parent::__construct($value);
    }

    private function getValidators(string $value): array
    {
        return [
            new SpecificationValidator(
                new StringIsAnEmailAddressSpecification(),
                sprintf('%s is not correct email.', $value)
            ),
        ];
    }
}
