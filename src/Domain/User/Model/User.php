<?php

declare(strict_types=1);

namespace App\Domain\User\Model;

use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;

class User extends DomainModel
{
    public function __construct(
        protected Uuid $id,
        private PersonalName $personalName,
        private EmailAddress $email,
        private PasswordHash $passwordHash,
        private Roles $roles,
    ) {
        parent::__construct($id);
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
