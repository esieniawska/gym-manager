<?php

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;

class TicketWithNumberOfDays extends Ticket
{
    public function __construct(
        private Uuid $id,
        private Money $price,
        private TicketStatus $status,
        private NumberOfDays $endDate,
    ) {
        parent::__construct($id, $price, $status);
    }

    public function getNumberOfDays(): NumberOfDays
    {
        return $this->endDate;
    }
}
