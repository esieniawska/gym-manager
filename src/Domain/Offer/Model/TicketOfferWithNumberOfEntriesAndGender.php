<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class TicketOfferWithNumberOfEntriesAndGender extends GenderOfferTicket implements OfferWithNumberOfEntries
{
    public function __construct(
        protected Uuid $id,
        protected OfferName $name,
        protected Money $price,
        protected OfferStatus $status,
        protected NumberOfEntries $numberOfEntries,
        protected Gender $gender,
    ) {
        parent::__construct($id, $name, $price, $status, $numberOfEntries, $gender);
    }
}
