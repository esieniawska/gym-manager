<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\PositiveValue;
use App\Domain\Shared\ValueObject\Uuid;

abstract class GenderOfferTicket extends OfferTicket
{
    public function __construct(
        protected Uuid $id,
        protected OfferName $name,
        protected Money $price,
        protected OfferStatus $status,
        protected PositiveValue $quantity,
        protected Gender $gender,
    ) {
        parent::__construct($id, $name, $price, $status, $quantity);
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function isAcceptedGender(Gender $gender): bool
    {
        return $this->gender->isTheSameType($gender);
    }
}
