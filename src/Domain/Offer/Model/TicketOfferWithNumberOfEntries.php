<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class TicketOfferWithNumberOfEntries extends OfferTicket
{
    public function __construct(
        private Uuid $id,
        private OfferName $name,
        private Money $price,
        private OfferStatus $status,
        private NumberOfEntries $numberOfEntries
    ) {
        parent::__construct($id, $name, $price, $status);
    }

    public function getNumberOfEntries(): NumberOfEntries
    {
        return $this->numberOfEntries;
    }
}
