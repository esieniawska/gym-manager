<?php

namespace App\Tests\Domain\User\Model;

use App\Domain\User\Model\Roles;
use PHPUnit\Framework\TestCase;

class RolesTest extends TestCase
{
    public function testCorrectRole(): void
    {
        $value = [Roles::ROLE_ADMIN];
        $roles = new Roles($value);
        $this->assertEquals($value, $roles->getValues());
    }

    public function testWrongRole(): void
    {
        $value = [Roles::ROLE_ADMIN, 'other-role'];
        $this->expectErrorMessage('Wrong roles: other-role');
        new Roles($value);
    }
}
