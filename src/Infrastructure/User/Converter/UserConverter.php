<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Converter;

use App\Domain\Shared\Exception\InvalidEmailAddressException;
use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;
use App\Domain\User\Entity\PasswordHash;
use App\Domain\User\Entity\Roles;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Entity\DbUser;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UserConverter
{
    public function convertDomainObjectToDbModel(User $user): DbUser
    {
        return new DbUser(
            RamseyUuid::fromString($user->getUuid()->getValue()),
            $user->getEmail()->getValue(),
            $user->getPasswordHash()->getValue(),
            $user->getPersonalName()->getFirstName(),
            $user->getPersonalName()->getLastName(),
            $user->getRoles()->getValues()
        );
    }

    /**
     * @throws InvalidEmailAddressException
     */
    public function convertDbModelToDomainObject(DbUser $dbUser): User
    {
        return new User(
            new Uuid($dbUser->getId()->toString()),
            new PersonalName($dbUser->getFirstName(), $dbUser->getLastName()),
            new EmailAddress($dbUser->getEmail()),
            new PasswordHash($dbUser->getPasswordHash()),
            new Roles($dbUser->getRoles())
        );
    }
}
