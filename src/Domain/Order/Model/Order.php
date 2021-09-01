<?php

declare(strict_types=1);

namespace App\Domain\Order\Model;

use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\Uuid;

class Order extends DomainModel
{
    public function __construct(
        private Uuid $id,
        private Buyer $buyer,
        private OrderItem $orderItem,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $createdAt
    ) {
        parent::__construct($id);
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getOrderItem(): OrderItem
    {
        return $this->orderItem;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }
}
