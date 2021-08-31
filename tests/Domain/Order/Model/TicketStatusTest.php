<?php

namespace App\Tests\Domain\Order\Model;

use App\Domain\Order\Model\TicketStatus;
use App\Domain\Shared\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class TicketStatusTest extends TestCase
{
    public function testCorrectStatus(): void
    {
        $ticketStatus = new TicketStatus(TicketStatus::ACTIVE);
        $this->assertEquals(TicketStatus::ACTIVE, (string) $ticketStatus);
    }

    public function testInvalidStatus(): void
    {
        $this->expectException(InvalidValueException::class);
        new TicketStatus('WRONG');
    }
}
