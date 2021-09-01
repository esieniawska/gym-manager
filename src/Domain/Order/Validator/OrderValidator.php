<?php

declare(strict_types=1);

namespace App\Domain\Order\Validator;

use App\Domain\Order\Exception\InvalidBuyerException;
use App\Domain\Order\Exception\InvalidOrderItemException;
use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Specification\OrderItemSpecificationValidatorsFactory;

class OrderValidator
{
    public function __construct(
        private OrderItemSpecificationValidatorsFactory $validatorsFactory
    ) {
    }

    /**
     * @throws InvalidOrderItemException
     * @throws InvalidBuyerException
     */
    public function ensureIsValidOrder(Order $order): void
    {
        $this->validateBuyer($order->getBuyer());
        $this->validateOrderItem($order->getBuyer(), $order->getOrderItem());
    }

    private function validateBuyer(Buyer $buyer): void
    {
        if (!$buyer->isActive()) {
            throw new InvalidBuyerException('Inactive buyer');
        }
    }

    /**
     * @throws InvalidOrderItemException
     */
    private function validateOrderItem(Buyer $buyer, OrderItem $orderItem): void
    {
        $orderItemValidators = $this->validatorsFactory->createValidators($orderItem, $buyer);

        foreach ($orderItemValidators as $orderItemValidator) {
            $orderItemValidator->checkIsValidOrderItem($orderItem);
        }
    }
}
