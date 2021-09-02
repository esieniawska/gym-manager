<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

use App\Domain\Shared\Event\DomainEvent;

abstract class OrderCreated implements DomainEvent
{
    public function __construct(
        protected string $id,
        protected string $buyerCardNumber,
        protected \DateTimeImmutable $startDate,
        protected int $quantity
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

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
