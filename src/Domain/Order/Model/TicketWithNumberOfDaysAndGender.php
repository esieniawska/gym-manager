<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;

class TicketWithNumberOfDaysAndGender extends TicketWithGender
{
    public function __construct(
        private Uuid $id,
        private Money $price,
        private TicketStatus $status,
        private NumberOfDays $endDate,
        private Gender $gender,
    ) {
        parent::__construct($id, $price, $status, $gender);
    }

    public function getNumberOfDays(): NumberOfDays
    {
        return $this->endDate;
    }
}