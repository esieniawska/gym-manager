<?php

namespace App\Tests\Domain\Order\Model;

use App\Domain\Order\Model\TicketStatus;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class TicketWithNumberOfDaysAndGenderTest extends TestCase
{
    public function testIsAcceptedGenderWhenClientHasOtherGender(): void
    {
        $ticket = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::FEMALE()
        );

        $this->assertFalse($ticket->isAcceptedGender(Gender::MALE()));
    }

    public function testIsAcceptedGenderWhenClientHasTheSameGender(): void
    {
        $ticket = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::FEMALE()
        );

        $this->assertTrue($ticket->isAcceptedGender(Gender::FEMALE()));
    }

    public function testIsActiveWhenClientHasOtherStatus(): void
    {
        $ticket = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::NOT_ACTIVE(),
            new NumberOfDays(3),
            Gender::FEMALE()
        );

        $this->assertFalse($ticket->isActive());
    }

    public function testIsActiveWhenClientHasTheSameStatus(): void
    {
        $ticket = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::FEMALE()
        );

        $this->assertTrue($ticket->isActive());
    }
}
