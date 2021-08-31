<?php

namespace App\Domain\Shared\Event;

interface DomainEventDispatcher
{
    public function dispatchEvent(DomainEvent $event): void;
}
