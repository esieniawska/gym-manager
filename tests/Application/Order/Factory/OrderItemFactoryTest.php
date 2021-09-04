<?php

namespace App\Tests\Application\Order\Factory;

use App\Application\Order\Factory\OrderItemFactory;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OrderItemFactoryTest extends TestCase
{
    use ProphecyTrait;

    private OrderItemFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new OrderItemFactory();
    }

    public function testCreateOrderItemTicketWithNumberOfDays(): void
    {
        $offer = new TicketOfferWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer'),
            new Money(1.33),
            OfferStatus::ACTIVE(),
            new NumberOfDays(2)
        );

        $result = $this->factory->createOrderItem($offer);
        $this->assertInstanceOf(TicketWithNumberOfDays::class, $result);
    }

    public function testCreateOrderItemTicketWithNumberOfEntries(): void
    {
        $offer = new TicketOfferWithNumberOfEntries(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer'),
            new Money(1.33),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(2)
        );

        $result = $this->factory->createOrderItem($offer);
        $this->assertInstanceOf(TicketWithNumberOfEntries::class, $result);
    }

    public function testCreateOrderItemWhenInvalidClass(): void
    {
        $offer = $this->prophesize(OfferTicket::class);
        $this->expectException(InvalidValueException::class);
        $this->factory->createOrderItem($offer->reveal());
    }
}
