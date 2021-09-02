<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class TicketOfferWithNumberOfEntries extends OfferTicket
{
    public function __construct(
        protected Uuid $id,
        protected OfferName $name,
        protected Money $price,
        protected OfferStatus $status,
        protected NumberOfEntries $numberOfEntries
    ) {
        parent::__construct($id, $name, $price, $status, $numberOfEntries);
    }
}
