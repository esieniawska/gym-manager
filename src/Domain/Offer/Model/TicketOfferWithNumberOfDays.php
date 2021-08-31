<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;

class TicketOfferWithNumberOfDays extends OfferTicket
{
    public function __construct(
        private Uuid $id,
        private OfferName $name,
        private Money $price,
        private OfferStatus $status,
        private NumberOfDays $numberOfEntries
    ) {
        parent::__construct($id, $name, $price, $status);
    }

    public function getNumberOfDays(): NumberOfDays
    {
        return $this->numberOfEntries;
    }
}
