<?php

namespace App\Tests\Domain\Client\Entity;

use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Shared\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    public function testCorrectPhoneNumber(): void
    {
        $phoneNumber = new PhoneNumber('123456789');
        $this->assertEquals('123456789', (string) $phoneNumber);
    }

    public function testInvalidPhoneNumber(): void
    {
        $this->expectException(InvalidValueException::class);
        new PhoneNumber('123a56789');
    }
}
