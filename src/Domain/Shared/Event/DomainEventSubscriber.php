<?php

namespace App\Domain\Shared\Event;

interface DomainEventSubscriber
{
    public static function subscribedTo(): array;
}
