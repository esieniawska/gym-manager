<?php

declare(strict_types=1);

namespace App\Domain\Order\Specification;

use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\OrderItem;

class OrderItemGenderIsCorrectSpecification implements OrderItemSpecification
{
    public function __construct(private Buyer $buyer)
    {
    }

    public function isSatisfiedBy(OrderItem $orderItem): bool
    {
        return $orderItem->isAcceptedGender($this->buyer->getGender());
    }
}
