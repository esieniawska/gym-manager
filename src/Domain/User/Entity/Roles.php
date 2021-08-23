<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\Entity\Enum\UserRole;
use App\Domain\User\Exception\WrongRoleException;

class Roles
{
    public function __construct(private array $values)
    {
        $this->validateRoles($values);
    }

    private function validateRoles(array $values)
    {
        $valuesOtherThanUserRoles = array_diff($values, UserRole::getRoles());
        if (!empty($valuesOtherThanUserRoles)) {
            throw new WrongRoleException(sprintf('Wrong roles: %s', implode(', ', $valuesOtherThanUserRoles)));
        }
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
