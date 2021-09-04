<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\CardNumber;

class Buyer
{
    public function __construct(private CardNumber $cardNumber)
    {
    }

    public function getCardNumber(): CardNumber
    {
        return $this->cardNumber;
    }
}
