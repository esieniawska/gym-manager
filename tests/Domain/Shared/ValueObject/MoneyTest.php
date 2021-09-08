<?php

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testSuccessfulCreatePrice(): void
    {
        $price = new Money(1.12);
        $this->assertEquals(1.12, $price->getFloatValue());
        $this->assertEquals(112, $price->getIntValue());
        $price = new Money(0);
        $this->assertEquals(0.0, $price->getFloatValue());
    }

    public function testFailedCreatePriceWhenNegativeNumber(): void
    {
        $this->expectException(InvalidValueException::class);
        new Money(-1.12);
    }

    public function testFailedCreatePriceWhenWrongValue(): void
    {
        $this->expectException(InvalidValueException::class);
        new Money(0.1234);
    }
}
