<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;

abstract class Ticket extends DomainModel implements OrderItem
{
    public function __construct(
        protected Uuid $id,
        protected Money $price,
        protected TicketStatus $status
    ) {
        parent::__construct($id);
    }

    public function isActive(): bool
    {
        return $this->status->isTheSameType(TicketStatus::ACTIVE());
    }
}
