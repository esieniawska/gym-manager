<?php

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\NumberOfDays;
use PHPUnit\Framework\TestCase;

class NumberOfDaysTest extends TestCase
{
    public function testSuccessfulCreateNumberOfDays(): void
    {
        $numberOfDays = new NumberOfDays(1);
        $this->assertEquals(1, $numberOfDays->getValue());
        $numberOfDays = new NumberOfDays(5);
        $this->assertEquals(5, $numberOfDays->getValue());
    }

    public function testFailedCreateWhenNegativeNumber(): void
    {
        $this->expectException(InvalidValueException::class);
        new NumberOfDays(-1);
    }

    public function testFailedCreateWhenZeroNumber(): void
    {
        $this->expectException(InvalidValueException::class);
        new NumberOfDays(0);
    }
}
