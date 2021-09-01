<?php

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use PHPUnit\Framework\TestCase;

class NumberOfEntriesTest extends TestCase
{
    public function testSuccessfulCreateNumberOfEntries(): void
    {
        $numberOfEntries = new NumberOfEntries(1);
        $this->assertEquals(1, $numberOfEntries->getValue());
        $numberOfEntries = new NumberOfDays(5);
        $this->assertEquals(5, $numberOfEntries->getValue());
    }

    public function testFailedCreateWhenNegativeNumber(): void
    {
        $this->expectException(InvalidValueException::class);
        new NumberOfEntries(-1);
    }

    public function testFailedCreateWhenZeroNumber(): void
    {
        $this->expectException(InvalidValueException::class);
        new NumberOfEntries(0);
    }
}
