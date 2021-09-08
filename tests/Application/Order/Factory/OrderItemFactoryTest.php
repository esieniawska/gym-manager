<?php

namespace App\Tests\Application\Order\Factory;

use App\Application\Offer\Dto\OfferDto;
use App\Application\Order\Factory\OrderItemFactory;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Shared\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class OrderItemFactoryTest extends TestCase
{
    private OrderItemFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new OrderItemFactory();
    }

    public function testCreateOrderItemTicketWithNumberOfDays(): void
    {
        $offer = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_DAYS,
            'name',
            0.05,
            OfferStatus::ACTIVE,
            4
        );
        $result = $this->factory->createOrderItem($offer);
        $this->assertInstanceOf(TicketWithNumberOfDays::class, $result);
    }

    public function testCreateOrderItemTicketWithNumberOfEntries(): void
    {
        $offer = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_ENTRIES,
            'name',
            0.05,
            OfferStatus::ACTIVE,
            4
        );

        $result = $this->factory->createOrderItem($offer);
        $this->assertInstanceOf(TicketWithNumberOfEntries::class, $result);
    }

    public function testCreateOrderItemWhenInvalidClass(): void
    {
        $offer = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            'wrong-type',
            'name',
            0.05,
            OfferStatus::ACTIVE,
            4
        );
        $this->expectException(InvalidValueException::class);
        $this->factory->createOrderItem($offer);
    }
}
