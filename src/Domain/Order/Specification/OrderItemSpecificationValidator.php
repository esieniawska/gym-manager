<?php

declare(strict_types=1);

namespace App\Domain\Order\Specification;

use App\Domain\Order\Exception\InvalidOrderItemException;
use App\Domain\Order\Model\OrderItem;

class OrderItemSpecificationValidator
{
    public function __construct(private OrderItemSpecification $specification, private string $errorMessage)
    {
    }

    public function checkIsValidOrderItem(OrderItem $orderItem): void
    {
        if (!$this->specification->isSatisfiedBy($orderItem)) {
            throw new InvalidOrderItemException($this->errorMessage);
        }
    }
}
