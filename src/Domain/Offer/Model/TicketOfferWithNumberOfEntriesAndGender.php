<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;

class TicketOfferWithNumberOfEntriesAndGender extends GenderOfferTicket
{
    public function __construct(
        private Uuid $id,
        private OfferName $name,
        private Money $price,
        private OfferStatus $status,
        private NumberOfEntries $numberOfEntries,
        private Gender $gender,
    ) {
        parent::__construct($id, $name, $price, $status, $gender);
    }

    public function getNumberOfEntries(): NumberOfEntries
    {
        return $this->numberOfEntries;
    }
}
