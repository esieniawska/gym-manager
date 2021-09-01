<?php

declare(strict_types=1);

namespace App\Domain\Order\Event;

use App\Domain\Order\Exception\InvalidOrderItemException;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Order\Model\TicketWithNumberOfEntriesAndGender;

class OrderCreatedEventFactory
{
    public function createEvent(Order $order): OrderCreated
    {
        $orderItem = $order->getOrderItem();
        switch ($orderItem) {
            case $orderItem instanceof TicketWithNumberOfEntries:
            case $orderItem instanceof TicketWithNumberOfEntriesAndGender:
                return $this->createOrderForTicketNumberOfEntriesCreated($order);
            case $orderItem instanceof TicketWithNumberOfDays:
            case $orderItem instanceof TicketWithNumberOfDaysAndGender:
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
            (int) $order->getOrderItem()->getNumberOfEntries()->getValue()
        );
    }

    private function createOrderForTicketNumberOfDaysCreated(Order $order): OrderForTicketNumberOfDaysCreated
    {
        return new OrderForTicketNumberOfDaysCreated(
            (string) $order->getId(),
            (string) $order->getBuyer()->getCardNumber(),
            $order->getStartDate(),
            $order->getOrderItem()->getNumberOfDays()->getValue()
        );
    }
}
