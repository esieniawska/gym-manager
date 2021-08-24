<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;

class User
{
    public function __construct(
        private Uuid $uuid,
        private PersonalName $personalName,
        private EmailAddress $email,
        private PasswordHash $passwordHash,
        private Roles $roles,
    ) {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function getPersonalName(): PersonalName
    {
        return $this->personalName;
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
