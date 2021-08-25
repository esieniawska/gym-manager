<?php

namespace App\Tests\Domain\Client\Entity;

use App\Domain\Client\Entity\CardNumber;
use App\Domain\Client\Exception\InvalidCardNumberException;
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
        $this->expectException(InvalidCardNumberException::class);
        new CardNumber('3da8%!@#$%^&*()0e770d2a0a17b7b82');
    }
}
