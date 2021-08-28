<?php

namespace App\Tests\Domain\User\Model;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\User\Model\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testSuccessfulCreatePassword(): void
    {
        $password = new Password('password');
        $this->assertEquals('password', (string) $password);
    }

    public function testFailedCreatePasswordWhenTooShort(): void
    {
        $this->expectException(InvalidValueException::class);
        new Password('1234567');
    }
}
