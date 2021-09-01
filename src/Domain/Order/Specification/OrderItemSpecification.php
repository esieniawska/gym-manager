<?php

namespace App\Domain\Order\Specification;

use App\Domain\Order\Model\OrderItem;

interface OrderItemSpecification
{
    public function isSatisfiedBy(OrderItem $orderItem): bool;
}
