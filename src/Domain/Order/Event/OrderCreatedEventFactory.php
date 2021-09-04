<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

use App\Domain\Order\Exception\InvalidOrderItemException;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfEntries;

class OrderCreatedEventFactory
{
    public function createEvent(Order $order): OrderCreated
    {
        $orderItem = $order->getOrderItem();
        switch ($orderItem) {
            case $orderItem instanceof TicketWithNumberOfEntries:
                return $this->createOrderForTicketNumberOfEntriesCreated($order);
            case $orderItem instanceof TicketWithNumberOfDays:
                return $this->createOrderForTicketNumberOfDaysCreated($order);
            default:
                throw new InvalidOrderItemException('Invalid order item');
        }
    }

    private function createOrderForTicketNumberOfEntriesCreated(Order $order): OrderForTicketNumberOfEntriesCreated
    {
        return new OrderForTicketNumberOfEntriesCreated(
            (string) $order->getId(),
            (string) $order->getBuyer()->getCardNumber(),
            $order->getStartDate(),
            (int) $order->getOrderItem()->getQuantity()->getValue()
        );
    }

    private function createOrderForTicketNumberOfDaysCreated(Order $order): OrderForTicketNumberOfDaysCreated
    {
        return new OrderForTicketNumberOfDaysCreated(
            (string) $order->getId(),
            (string) $order->getBuyer()->getCardNumber(),
            $order->getStartDate(),
            $order->getOrderItem()->getQuantity()->getValue()
        );
    }
}
