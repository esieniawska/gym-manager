<?php

namespace App\Tests\Infrastructure\Shared\Event;

use App\Domain\Order\Event\OrderForTicketNumberOfEntriesCreated;
use App\Domain\Shared\Event\DomainEvent;
use App\Infrastructure\Shared\Event\SymfonyDomainEventDispatcher;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SymfonyDomainEventDispatcherTest extends TestCase
{
    use ProphecyTrait;

    public function testDispatchEvent(): void
    {
        $eventDispatcherMock = $this->prophesize(EventDispatcherInterface::class);
        $eventDispatcherMock->dispatch(Argument::type(DomainEvent::class))->shouldBeCalled();

        $symfonyDomainEventDispatcher = new SymfonyDomainEventDispatcher($eventDispatcherMock->reveal());
        $event = new OrderForTicketNumberOfEntriesCreated(
            '300bff1c-171d-4065-bf76-97ca98574667',
            'caabacb3554c96008ba346a61d1839fa',
            new \DateTimeImmutable(),
            3
        );
        $symfonyDomainEventDispatcher->dispatchEvent($event);
    }
}
