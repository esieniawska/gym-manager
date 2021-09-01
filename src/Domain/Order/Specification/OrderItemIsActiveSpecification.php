<?php

declare(strict_types=1);

namespace App\Domain\Order\Specification;

use App\Domain\Order\Model\OrderItem;

class OrderItemIsActiveSpecification implements OrderItemSpecification
{
    public function isSatisfiedBy(OrderItem $orderItem): bool
    {
        return $orderItem->isActive();
    }
}
