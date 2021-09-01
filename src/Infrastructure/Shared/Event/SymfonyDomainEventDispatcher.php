<?php

namespace App\Infrastructure\Shared\Event;

use App\Domain\Shared\Event\DomainEvent;
use App\Domain\Shared\Event\DomainEventDispatcher;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SymfonyDomainEventDispatcher implements DomainEventDispatcher
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatchEvent(DomainEvent $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
