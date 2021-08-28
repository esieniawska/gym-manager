<?php

declare(strict_types=1);

namespace App\Domain\Client\Model;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\StringHasValidPatternSpecification;
use App\Domain\Shared\ValueObject\StringValueObject;

class CardNumber extends StringValueObject
{
    public const NUMBER_LENGTH = 32;
    public const NUMBER_PATTERN = '/^[0-9a-f]{32}$/';

    public function __construct(protected string $value)
    {
        $this->ensureIsSatisfiedValue($value, $this->getValidators());

        parent::__construct($value);
    }

    private function getValidators(): array
    {
        return [
            new SpecificationValidator(
                new StringHasValidPatternSpecification(self::NUMBER_PATTERN),
                'Invalid card number format.'
            ),
        ];
    }
}
