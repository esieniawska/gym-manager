<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class TicketWithNumberOfEntries extends Ticket
{
    public function __construct(
        private Uuid $id,
        private Money $price,
        private TicketStatus $status,
        private NumberOfEntries $numberOfEntries,
    ) {
        parent::__construct($id, $price, $status);
    }

    public function getNumberOfEntries(): NumberOfEntries
    {
        return $this->numberOfEntries;
    }
}
