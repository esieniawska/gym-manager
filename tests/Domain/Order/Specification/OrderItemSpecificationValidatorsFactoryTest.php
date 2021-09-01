<?php

namespace App\Tests\Domain\Order\Specification;

use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\BuyerStatus;
use App\Domain\Order\Model\TicketStatus;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Specification\OrderItemSpecificationValidatorsFactory;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class OrderItemSpecificationValidatorsFactoryTest extends TestCase
{
    public function testCreateValidatorsForGenderOfferTicket(): void
    {
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::FEMALE()
        );

        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new PersonalName('Joe', 'Smith'),
            Gender::FEMALE(),
            BuyerStatus::NOT_ACTIVE()
        );

        $factory = new OrderItemSpecificationValidatorsFactory();
        $validators = $factory->createValidators($orderItem, $buyer);
        $this->assertCount(2, $validators);
    }

    public function testCreateValidatorsForDefaultOrderItem(): void
    {
        $orderItem = new TicketWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3)
        );

        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new PersonalName('Joe', 'Smith'),
            Gender::FEMALE(),
            BuyerStatus::NOT_ACTIVE()
        );

        $factory = new OrderItemSpecificationValidatorsFactory();
        $validators = $factory->createValidators($orderItem, $buyer);
        $this->assertCount(1, $validators);
    }
}
