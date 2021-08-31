<?php

declare(strict_types=1);

namespace App\Domain\Order\Specification;

use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Model\TicketWithGender;

class OrderItemSpecificationValidatorsFactory
{
    public function createValidators(OrderItem $orderItem, Buyer $buyer): array
    {
        switch ($orderItem) {
            case $orderItem instanceof TicketWithGender:
                return [
                    new OrderItemSpecificationValidator(new OrderItemIsActiveSpecification(), 'Inactive orderItem'),
                    new OrderItemSpecificationValidator(new OrderItemGenderIsCorrectSpecification($buyer), 'Invalid gender'),
                ];
            default:
                return [
                    new OrderItemSpecificationValidator(new OrderItemIsActiveSpecification(), 'Inactive orderItem'),
                ];
        }
    }
}
