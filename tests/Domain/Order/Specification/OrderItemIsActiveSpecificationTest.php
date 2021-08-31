<?php

namespace App\Tests\Domain\Order\Specification;

use App\Domain\Order\Model\NumberOfDays;
use App\Domain\Order\Model\TicketStatus;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Specification\OrderItemIsActiveSpecification;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class OrderItemIsActiveSpecificationTest extends TestCase
{
    public function testIsSatisfiedByWhenOrderIsActive(): void
    {
        $specification = new OrderItemIsActiveSpecification();
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $this->assertTrue($specification->isSatisfiedBy($orderItem));
    }

    public function testIsSatisfiedByWhenOrderNotActive(): void
    {
        $specification = new OrderItemIsActiveSpecification();
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::NOT_ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $this->assertFalse($specification->isSatisfiedBy($orderItem));
    }
}
