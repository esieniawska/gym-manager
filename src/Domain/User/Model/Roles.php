<?php

declare(strict_types=1);

namespace App\Domain\User\Model;

use App\Domain\Shared\Specification\SpecificationValidator;
use App\Domain\Shared\Specification\ValuesAreBetweenAcceptedValuesSpecification;
use App\Domain\Shared\ValueObject\ValueObject;

class Roles extends ValueObject
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function __construct(private array $values)
    {
        $this->ensureIsSatisfiedValue($values, $this->getValidators());
    }

    private function getValidators(): array
    {
        return [
            new SpecificationValidator(
                new ValuesAreBetweenAcceptedValuesSpecification($this->getRoles()),
                'Invalid roles.'
            ),
        ];
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getRoles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
        ];
    }
}
