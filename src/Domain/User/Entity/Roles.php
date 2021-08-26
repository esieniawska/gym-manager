<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\Exception\InvalidRoleException;

class Roles
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function __construct(private array $values)
    {
        $this->validateRoles($values);
    }

    private function validateRoles(array $values)
    {
        $valuesOtherThanUserRoles = array_diff($values, self::getRoles());
        if (!empty($valuesOtherThanUserRoles)) {
            throw new InvalidRoleException(sprintf('Wrong roles: %s', implode(', ', $valuesOtherThanUserRoles)));
        }
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public static function getRoles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
        ];
    }
}
