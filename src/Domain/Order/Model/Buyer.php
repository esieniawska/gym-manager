<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;

class Buyer
{
    public function __construct(
        private CardNumber $cardNumber,
        private PersonalName $personalName,
        private Gender $gender,
        private BuyerStatus $buyerStatus
    ) {
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function isActive(): bool
    {
        return $this->buyerStatus->isTheSameType(BuyerStatus::ACTIVE());
    }

    public function getCardNumber(): CardNumber
    {
        return $this->cardNumber;
    }
}
