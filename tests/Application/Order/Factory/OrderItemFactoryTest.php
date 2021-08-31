<?php

namespace App\Tests\Application\Order\Factory;

use App\Application\Order\Factory\OrderItemFactory;
use App\Domain\Offer\Model\NumberOfDays;
use App\Domain\Offer\Model\NumberOfEntries;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Offer\OfferFacade;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Order\Model\TicketWithNumberOfEntriesAndGender;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class OrderItemFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferFacade $offerFacadeMock;
    private OrderItemFactory $factory;

    protected function setUp(): void
    {
        $this->offerFacadeMock = $this->prophesize(OfferFacade::class);
        $this->factory = new OrderItemFactory($this->offerFacadeMock->reveal());
    }

    public function testCreateOrderItemTicketWithNumberOfDays(): void
    {
        $this->offerFacadeMock
            ->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn(new TicketOfferWithNumberOfDays(
                new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                new OfferName('offer'),
                new Money(1.33),
                OfferStatus::ACTIVE(),
                new NumberOfDays(2)
            ));

        $result = $this->factory->createOrderItemFromOfferId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertInstanceOf(TicketWithNumberOfDays::class, $result);
    }

    public function testCreateOrderItemTicketWithNumberOfEntries(): void
    {
        $this->offerFacadeMock
            ->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn(new TicketOfferWithNumberOfEntries(
                new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                new OfferName('offer'),
                new Money(1.33),
                OfferStatus::ACTIVE(),
                new NumberOfEntries(2)
            ));

        $result = $this->factory->createOrderItemFromOfferId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertInstanceOf(TicketWithNumberOfEntries::class, $result);
    }

    public function testCreateOrderItemTicketWithNumberOfDaysAndGender(): void
    {
        $this->offerFacadeMock
            ->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn(new TicketOfferWithNumberOfDaysAndGender(
                new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                new OfferName('offer'),
                new Money(1.33),
                OfferStatus::ACTIVE(),
                new NumberOfDays(2),
                Gender::MALE()
            ));

        $result = $this->factory->createOrderItemFromOfferId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertInstanceOf(TicketWithNumberOfDaysAndGender::class, $result);
    }

    public function testCreateOrderItemTicketWithNumberOfEntriesAndGender(): void
    {
        $this->offerFacadeMock
            ->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn(new TicketOfferWithNumberOfEntriesAndGender(
                new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                new OfferName('offer'),
                new Money(1.33),
                OfferStatus::ACTIVE(),
                new NumberOfEntries(2),
                Gender::MALE()
            ));

        $result = $this->factory->createOrderItemFromOfferId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertInstanceOf(TicketWithNumberOfEntriesAndGender::class, $result);
    }

    public function testCreateOrderItemWhenInvalidClass(): void
    {
        $offer = $this->prophesize(OfferTicket::class);
        $this->offerFacadeMock
            ->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn($offer->reveal());

        $this->expectException(InvalidValueException::class);
        $this->factory->createOrderItemFromOfferId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
    }
}
