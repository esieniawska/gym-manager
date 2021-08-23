<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Converter;

use App\Domain\Shared\Model\StringValueObject;
use App\Domain\User\Entity\EmailAddress;
use App\Domain\User\Entity\PasswordHash;
use App\Domain\User\Entity\Roles;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\WrongEmailAddressException;
use App\Infrastructure\User\Entity\DbUser;

class UserConverter
{
    public function convertDomainObjectToDbModel(User $user): DbUser
    {
        return new DbUser(
            $user->getEmail()->getValue(),
            $user->getPasswordHash()->getValue(),
            $user->getFirstName()->getValue(),
            $user->getLastName()->getValue(),
            $user->getRoles()->getValues()
        );
    }

    /**
     * @throws WrongEmailAddressException
     */
    public function convertDbModelToDomainObject(DbUser $dbUser): User
    {
        return new User(
            new StringValueObject($dbUser->getFirstName()),
            new StringValueObject($dbUser->getLastName()),
            new EmailAddress($dbUser->getEmail()),
            new PasswordHash($dbUser->getPasswordHash()),
            new Roles($dbUser->getRoles())
        );
    }
}
