<?php

declare(strict_types=1);

namespace App\Domain\User\Model;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\StringMinimumLengthSpecification;
use App\Domain\Shared\ValueObject\StringValueObject;

class Password extends StringValueObject
{
    private const MIN_LENGTH = 8;

    public function __construct(string $value)
    {
        $this->ensureIsSatisfiedValue($value, $this->getValidators());

        parent::__construct($value);
    }

    private function getValidators(): array
    {
        return [
            new SpecificationValidator(
                new StringMinimumLengthSpecification(self::MIN_LENGTH),
                sprintf('Password must have at least %s characters.', self::MIN_LENGTH)
            ),
        ];
    }
}
