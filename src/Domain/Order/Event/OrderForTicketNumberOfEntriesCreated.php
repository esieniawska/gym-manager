<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

class OrderForTicketNumberOfEntriesCreated extends OrderCreated
{
    public function __construct(
        protected string $id,
        protected string $buyerCardNumber,
        protected \DateTimeImmutable $startDate,
        private int $numberOfEntries
    ) {
        parent::__construct($id, $buyerCardNumber, $startDate);
    }

    public function getNumberOfEntries(): int
    {
        return $this->numberOfEntries;
    }
}
