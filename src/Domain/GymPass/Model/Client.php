<?php

declare(strict_types=1);

namespace App\Domain\GymPass\Model;

use App\Domain\Shared\ValueObject\CardNumber;

class Client
{
    public function __construct(private CardNumber $cardNumber)
    {
    }

    public function getCardNumber(): CardNumber
    {
        return $this->cardNumber;
    }

    public function isTheSameClient(CardNumber $cardNumber): bool
    {
        return (string) $this->cardNumber === (string) $cardNumber;
    }
}
