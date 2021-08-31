<?php

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\CardNumber;
use PHPUnit\Framework\TestCase;

class CardNumberTest extends TestCase
{
    public function testCorrectCardNumber(): void
    {
        $cardNumber = new CardNumber('3da8b78de7732860e770d2a0a17b7b82');
        $this->assertEquals('3da8b78de7732860e770d2a0a17b7b82', (string) $cardNumber);
    }

    public function testInvalidCardNumber(): void
    {
        $this->expectException(InvalidValueException::class);
        new CardNumber('3da8%!@#$%^&*()0e770d2a0a17b7b82');
    }
}
