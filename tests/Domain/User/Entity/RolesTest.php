<?php

namespace App\Tests\Domain\User\Entity;

use App\Domain\User\Entity\Enum\UserRole;
use App\Domain\User\Entity\Roles;
use PHPUnit\Framework\TestCase;

class RolesTest extends TestCase
{
    public function testCorrectRole(): void
    {
        $value = [UserRole::ROLE_ADMIN];
        $roles = new Roles($value);
        $this->assertEquals($value, $roles->getValues());
    }

    public function testWrongRole(): void
    {
        $value = [UserRole::ROLE_ADMIN, 'other-role'];
        $this->expectErrorMessage('Wrong roles: other-role');
        new Roles($value);
    }
}
