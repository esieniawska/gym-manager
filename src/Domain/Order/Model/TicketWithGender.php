<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;

abstract class TicketWithGender extends Ticket
{
    public function __construct(
        private Uuid $id,
        private Money $price,
        private TicketStatus $status,
        private Gender $gender,
    ) {
        parent::__construct($id, $price, $status);
    }

    public function isAcceptedGender(Gender $gender): bool
    {
        return $this->gender->isTheSameType($gender);
    }
}
