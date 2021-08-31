<?php

namespace App\Tests\Domain\Order\Specification;

use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\NumberOfDays;
use App\Domain\Order\Model\TicketStatus;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Specification\OrderItemGenderIsCorrectSpecification;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OrderItemGenderIsCorrectSpecificationTest extends TestCase
{
    use ProphecyTrait;

    public function testIsSatisfiedByWhenBuyerHasOtherGender(): void
    {
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $buyer = $this->prophesize(Buyer::class);
        $buyer->getGender()->willReturn(Gender::FEMALE());
        $specification = new OrderItemGenderIsCorrectSpecification($buyer->reveal());
        $this->assertFalse($specification->isSatisfiedBy($orderItem));
    }

    public function testIsSatisfiedByWhenBuyerHasAcceptedGender(): void
    {
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $buyer = $this->prophesize(Buyer::class);
        $buyer->getGender()->willReturn(Gender::MALE());
        $specification = new OrderItemGenderIsCorrectSpecification($buyer->reveal());
        $this->assertTrue($specification->isSatisfiedBy($orderItem));
    }
}
