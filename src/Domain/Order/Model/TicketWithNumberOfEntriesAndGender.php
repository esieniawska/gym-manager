<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class TicketWithNumberOfEntriesAndGender extends TicketWithGender
{
    public function __construct(
        protected Uuid $id,
        protected Money $price,
        protected TicketStatus $status,
        protected NumberOfEntries $numberOfEntries,
        protected Gender $gender,
    ) {
        parent::__construct($id, $price, $status, $numberOfEntries, $gender);
    }
}
