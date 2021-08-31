<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\StringMinimumLengthSpecification;
use App\Domain\Shared\ValueObject\StringValueObject;

class OfferName extends StringValueObject
{
    private const MIN_LENGTH = 3;

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
                sprintf('Name must have at least %s characters.', self::MIN_LENGTH)
            ),
        ];
    }
}
