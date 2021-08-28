<?php

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\EmailAddress;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    public function testCorrectEmailAddress(): void
    {
        $email = 'joe@example.com';
        $emailObject = new EmailAddress($email);
        $this->assertEquals($email, $emailObject->getValue());
    }

    public function testInvalidEmailAddress(): void
    {
        $email = 'joeexample.com';
        $this->expectException(InvalidValueException::class);
        new EmailAddress($email);
    }
}
