<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\PositiveValue;
use App\Domain\Shared\ValueObject\Uuid;

abstract class TicketWithGender extends Ticket
{
    public function __construct(
        protected Uuid $id,
        protected Money $price,
        protected TicketStatus $status,
        protected PositiveValue $quantity,
        protected Gender $gender,
    ) {
        parent::__construct($id, $price, $status, $quantity);
    }

    public function isAcceptedGender(Gender $gender): bool
    {
        return $this->gender->isTheSameType($gender);
    }
}
