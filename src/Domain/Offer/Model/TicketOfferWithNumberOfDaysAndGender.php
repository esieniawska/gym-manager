<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;

class TicketOfferWithNumberOfDaysAndGender extends GenderOfferTicket implements OfferWithNumberOfDays
{
    public function __construct(
        protected Uuid $id,
        protected OfferName $name,
        protected Money $price,
        protected OfferStatus $status,
        protected NumberOfDays $numberOfEntries,
        protected Gender $gender,
    ) {
        parent::__construct($id, $name, $price, $status, $numberOfEntries, $gender);
    }
}
