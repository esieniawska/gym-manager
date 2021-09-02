<?php

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;

class TicketWithNumberOfDays extends Ticket
{
    public function __construct(
        protected Uuid $id,
        protected Money $price,
        protected TicketStatus $status,
        protected NumberOfDays $numberOfDays,
    ) {
        parent::__construct($id, $price, $status, $numberOfDays);
    }
}
