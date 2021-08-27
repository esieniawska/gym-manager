<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Converter;

use App\Domain\Shared\Exception\InvalidEmailAddressException;
use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Model\PasswordHash;
use App\Domain\User\Model\Roles;
use App\Domain\User\Model\User;
use App\Infrastructure\Shared\Converter\DbCollectionConverter;
use App\Infrastructure\Shared\Entity\DbEntity;
use App\Infrastructure\User\Entity\DbUser;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UserDbConverter extends DbCollectionConverter
{
    public function convertDomainObjectToDbModel(DomainModel $user): DbUser
    {
        return new DbUser(
            RamseyUuid::fromString((string) $user->getId()),
            (string) $user->getEmail(),
            (string) $user->getPasswordHash(),
            $user->getPersonalName()->getFirstName(),
            $user->getPersonalName()->getLastName(),
            $user->getRoles()->getValues()
        );
    }

    /**
     * @throws InvalidEmailAddressException
     */
    public function convertDbModelToDomainObject(DbEntity $dbUser): User
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
