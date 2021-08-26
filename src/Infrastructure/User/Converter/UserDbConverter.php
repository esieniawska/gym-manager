<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Converter;

use App\Domain\Shared\Exception\InvalidEmailAddressException;
use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;
use App\Domain\User\Entity\PasswordHash;
use App\Domain\User\Entity\Roles;
use App\Domain\User\Entity\User;
use App\Infrastructure\Shared\Converter\BaseDbConverter;
use App\Infrastructure\Shared\Entity\DbEntity;
use App\Infrastructure\User\Entity\DbUser;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UserDbConverter extends BaseDbConverter
{
    public function convertDomainObjectToDbModel(DomainModel $user): DbUser
    {
        return new DbUser(
            RamseyUuid::fromString((string) $user->getUuid()),
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
