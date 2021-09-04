<?php

namespace App\Tests\Domain\Order\Event;

use App\Domain\Order\Event\OrderCreatedEventFactory;
use App\Domain\Order\Event\OrderForTicketNumberOfDaysCreated;
use App\Domain\Order\Event\OrderForTicketNumberOfEntriesCreated;
use App\Domain\Order\Exception\InvalidOrderItemException;
use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\Ticket;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OrderCreatedEventFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testCreateEventForTicketNumberOfEntries(): void
    {
        $orderItem = new TicketWithNumberOfEntries(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            new NumberOfEntries(3),
        );

        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
        );

        $order = new Order(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $buyer,
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $factory = new OrderCreatedEventFactory();
        $result = $factory->createEvent($order);
        $this->assertInstanceOf(OrderForTicketNumberOfEntriesCreated::class, $result);
    }

    public function testCreateEventForTicketWithNumberOfDays(): void
    {
        $orderItem = new TicketWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            new NumberOfDays(3),
        );

        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
        );

        $order = new Order(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $buyer,
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $factory = new OrderCreatedEventFactory();
        $result = $factory->createEvent($order);
        $this->assertInstanceOf(OrderForTicketNumberOfDaysCreated::class, $result);
    }

    public function testCreateEventForInvalidClass(): void
    {
        $orderItem = $this->prophesize(Ticket::class);
        $buyer = $this->prophesize(Buyer::class);

        $order = new Order(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $buyer->reveal(),
            $orderItem->reveal(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $factory = new OrderCreatedEventFactory();

        $this->expectException(InvalidOrderItemException::class);
        $result = $factory->createEvent($order);
        $this->assertInstanceOf(OrderForTicketNumberOfDaysCreated::class, $result);
    }
}
