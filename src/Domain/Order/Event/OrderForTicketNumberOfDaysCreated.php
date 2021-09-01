<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

class OrderForTicketNumberOfDaysCreated extends OrderCreated
{
    public function __construct(
        private string $id,
        private string $buyerCardNumber,
        private \DateTimeImmutable $startDate,
        private int $numberOfDays
    ) {
        parent::__construct($id, $buyerCardNumber, $startDate);
    }

    public function getNumberOfDays(): int
    {
        return $this->numberOfDays;
    }
}
