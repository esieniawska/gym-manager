<?php

namespace App\Tests\Infrastructure\User\Converter;

use App\Domain\Shared\Model\StringValueObject;
use App\Domain\User\Entity\EmailAddress;
use App\Domain\User\Entity\Enum\UserRole;
use App\Domain\User\Entity\PasswordHash;
use App\Domain\User\Entity\Roles;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Converter\UserConverter;
use App\Infrastructure\User\Entity\DbUser;
use PHPUnit\Framework\TestCase;

class UserConverterTest extends TestCase
{
    public function testConvertDomainObjectToDbModel(): void
    {
        $user = new User(
            new StringValueObject('Joe'),
            new StringValueObject('Smith'),
            new EmailAddress('test@example.com'),
            new PasswordHash('hash'),
            new Roles([UserRole::ROLE_USER])
        );

        $dbUser = new DbUser(
            'test@example.com',
            'hash',
            'Joe',
            'Smith',
            [UserRole::ROLE_USER]
        );
        $converter = new UserConverter();
        $result = $converter->convertDomainObjectToDbModel($user);

        $this->assertEquals($dbUser, $result);
    }

    public function testConvertDbModelToDomainObject(): void
    {
        $user = new User(
            new StringValueObject('Joe'),
            new StringValueObject('Smith'),
            new EmailAddress('test@example.com'),
            new PasswordHash('hash'),
            new Roles([UserRole::ROLE_USER])
        );

        $dbUser = new DbUser(
            'test@example.com',
            'hash',
            'Joe',
            'Smith',
            [UserRole::ROLE_USER]
        );
        $converter = new UserConverter();
        $result = $converter->convertDbModelToDomainObject($dbUser);

        $this->assertEquals($user, $result);
    }
}
