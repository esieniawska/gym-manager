<?php

namespace App\Tests\Domain\Order\Model;

use App\Domain\Order\Model\BuyerStatus;
use App\Domain\Shared\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class BuyerStatusTest extends TestCase
{
    public function testCorrectStatus(): void
    {
        $buyerStatus = new BuyerStatus(BuyerStatus::ACTIVE);
        $this->assertEquals(BuyerStatus::ACTIVE, (string) $buyerStatus);
    }

    public function testInvalidStatus(): void
    {
        $this->expectException(InvalidValueException::class);
        new BuyerStatus('WRONG');
    }
}
