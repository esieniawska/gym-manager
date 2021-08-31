<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

use App\Domain\Shared\Event\DomainEvent;

abstract class OrderCreated implements DomainEvent
{
    public function __construct(
        private string $id,
        private string $buyerCardNumber,
        private \DateTimeImmutable $startDate
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBuyerCardNumber(): string
    {
        return $this->buyerCardNumber;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }
}
