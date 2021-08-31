<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

use App\Domain\Order\Model\Order;
use App\Domain\Shared\Event\DomainEventDispatcher;

class DomainEventPublisher
{
    public function __construct(
        private DomainEventDispatcher $eventDispatcher,
        private OrderCreatedEventFactory $createdEventFactory
    ) {
    }

    public function publishOrderCreatedEvent(Order $order): void
    {
        $this->eventDispatcher->dispatchEvent(
            $this->createdEventFactory->createEvent($order)
        );
    }
}
