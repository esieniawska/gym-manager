<?php

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;

class TicketWithNumberOfDays extends Ticket
{
    public function __construct(
        private Uuid $id,
        private Money $price,
        private TicketStatus $status,
        private NumberOfDays $numberOfDays,
    ) {
        parent::__construct($id, $price, $status);
    }
}
