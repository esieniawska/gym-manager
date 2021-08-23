<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Model\StringValueObject;

class User
{
    public function __construct(
        private StringValueObject $firstName,
        private StringValueObject $lastName,
        private EmailAddress $email,
        private PasswordHash $passwordHash,
        private Roles $roles,
    ) {
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function getFirstName(): StringValueObject
    {
        return $this->firstName;
    }

    public function getLastName(): StringValueObject
    {
        return $this->lastName;
    }

    public function getPasswordHash(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function getRoles(): Roles
    {
        return $this->roles;
    }
}
