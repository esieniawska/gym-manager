<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

class OrderForTicketNumberOfEntriesCreated extends OrderCreated
{
    public function __construct(
        protected string $id,
        protected string $buyerCardNumber,
        protected \DateTimeImmutable $startDate,
        protected int $numberOfEntries
    ) {
        parent::__construct($id, $buyerCardNumber, $startDate, $numberOfEntries);
    }
}
