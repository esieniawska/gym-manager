<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

class OrderForTicketNumberOfDaysCreated extends OrderCreated
{
    public function __construct(
        protected string $id,
        protected string $buyerCardNumber,
        protected \DateTimeImmutable $startDate,
        protected int $numberOfDays
    ) {
        parent::__construct($id, $buyerCardNumber, $startDate, $numberOfDays);
    }
}
