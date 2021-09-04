<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\PositiveValue;
use App\Domain\Shared\ValueObject\Uuid;

abstract class Ticket implements OrderItem
{
    public function __construct(
        protected Uuid $offerId,
        protected Money $price,
        protected PositiveValue $quantity
    ) {
    }

    public function getQuantity(): PositiveValue
    {
        return $this->quantity;
    }

    public function getOfferId(): Uuid
    {
        return $this->offerId;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }
}
