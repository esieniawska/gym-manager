<?php

namespace App\Tests\Infrastructure\User\Converter;

use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;
use App\Domain\User\Entity\PasswordHash;
use App\Domain\User\Entity\Roles;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Converter\UserDbConverter;
use App\Infrastructure\User\Entity\DbUser;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UserDbConverterTest extends TestCase
{
    public function testConvertDomainObjectToDbModel(): void
    {
        $user = new User(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new EmailAddress('test@example.com'),
            new PasswordHash('hash'),
            new Roles([Roles::ROLE_USER])
        );

        $dbUser = new DbUser(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'test@example.com',
            'hash',
            'Joe',
            'Smith',
            [Roles::ROLE_USER]
        );
        $converter = new UserDbConverter();
        $result = $converter->convertDomainObjectToDbModel($user);

        $this->assertEquals($dbUser, $result);
    }

    public function testConvertDbModelToDomainObject(): void
    {
        $user = new User(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new EmailAddress('test@example.com'),
            new PasswordHash('hash'),
            new Roles([Roles::ROLE_USER])
        );

        $dbUser = new DbUser(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'test@example.com',
            'hash',
            'Joe',
            'Smith',
            [Roles::ROLE_USER]
        );
        $converter = new UserDbConverter();
        $result = $converter->convertDbModelToDomainObject($dbUser);

        $this->assertEquals($user, $result);
    }
}
