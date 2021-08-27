<?php

declare(strict_types=1);

namespace App\Domain\User\Model;

use App\Domain\User\Exception\InvalidRoleException;

class Roles
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function __construct(private array $values)
    {
        $this->checkAreValidRoles($values);
    }

    private function checkAreValidRoles(array $values): void
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
