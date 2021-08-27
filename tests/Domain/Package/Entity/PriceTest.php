<?php

namespace App\Tests\Domain\Package\Entity;

use App\Domain\Package\Entity\Money;
use App\Domain\Package\Exception\InvalidPriceException;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testSuccessfulCreatePrice(): void
    {
        $price = new Money(1.12);
        $this->assertEquals(1.12, $price->getPrice());
        $price = new Money(0);
        $this->assertEquals(0, $price->getPrice());
    }

    public function testFailedCreatePriceWhenNegativeNumber(): void
    {
        $this->expectException(InvalidPriceException::class);
        new Money(-1.12);
    }

    public function testFailedCreatePriceWhenWrongValue(): void
    {
        $this->expectException(InvalidPriceException::class);
        new Money(0.1234);
    }
}
