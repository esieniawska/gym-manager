<?php

namespace App\Tests\Domain\Order\Event;

use App\Domain\Order\Event\DomainEventPublisher;
use App\Domain\Order\Event\OrderCreatedEventFactory;
use App\Domain\Order\Event\OrderForTicketNumberOfDaysCreated;
use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Shared\Event\DomainEvent;
use App\Domain\Shared\Event\DomainEventDispatcher;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class DomainEventPublisherTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|DomainEventDispatcher $eventDispatcherMock;
    private ObjectProphecy|OrderCreatedEventFactory $createdEventFactoryMock;
    private DomainEventPublisher $eventPublisher;

    protected function setUp(): void
    {
        $this->eventDispatcherMock = $this->prophesize(DomainEventDispatcher::class);
        $this->createdEventFactoryMock = $this->prophesize(OrderCreatedEventFactory::class);
        $this->eventPublisher = new DomainEventPublisher(
            $this->eventDispatcherMock->reveal(),
            $this->createdEventFactoryMock->reveal()
        );
    }

    public function testPublishOrderCreatedEvent(): void
    {
        $this->eventDispatcherMock
            ->dispatchEvent(Argument::type(DomainEvent::class))
            ->shouldBeCalled();

        $event = new OrderForTicketNumberOfDaysCreated(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            new \DateTimeImmutable(),
            5
        );

        $this->createdEventFactoryMock->createEvent(Argument::type(Order::class))
            ->willReturn($event)
            ->shouldBeCalled();

        $orderItem = $this->prophesize(TicketWithNumberOfDaysAndGender::class);
        $buyer = $this->prophesize(Buyer::class);
        $order = new Order(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $buyer->reveal(),
            $orderItem->reveal(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $this->eventPublisher->publishOrderCreatedEvent($order);
    }
}
