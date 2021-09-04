<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class TicketWithNumberOfEntries extends Ticket
{
    public function __construct(
        protected Uuid $offerId,
        protected Money $price,
        protected NumberOfEntries $numberOfEntries,
    ) {
        parent::__construct($offerId, $price, $numberOfEntries);
    }
}
