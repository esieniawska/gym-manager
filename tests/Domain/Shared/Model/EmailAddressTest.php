<?php

namespace App\Tests\Domain\Shared\Model;

use App\Domain\Shared\Exception\InvalidEmailAddressException;
use App\Domain\Shared\Model\EmailAddress;
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
        $this->expectException(InvalidEmailAddressException::class);
        new EmailAddress($email);
    }
}
