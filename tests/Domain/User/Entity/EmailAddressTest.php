<?php

namespace App\Tests\Domain\User\Entity;

use App\Domain\User\Entity\EmailAddress;
use App\Domain\User\Exception\WrongEmailAddressException;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    public function testCorrectEmailAddress(): void
    {
        $email = 'joe@example.com';
        $emailObject = new EmailAddress($email);
        $this->assertEquals($email, $emailObject->getValue());
    }

    public function testWrongEmailAddress(): void
    {
        $email = 'joeexample.com';
        $this->expectException(WrongEmailAddressException::class);
        new EmailAddress($email);
    }
}
