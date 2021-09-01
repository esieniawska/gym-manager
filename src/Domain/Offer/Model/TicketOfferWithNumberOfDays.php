<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;

class TicketOfferWithNumberOfDays extends OfferTicket
{
    public function __construct(
        protected Uuid $id,
        protected OfferName $name,
        protected Money $price,
        protected OfferStatus $status,
        private NumberOfDays $numberOfEntries
    ) {
        parent::__construct($id, $name, $price, $status);
    }

    public function getNumberOfDays(): NumberOfDays
    {
        return $this->numberOfEntries;
    }
}
