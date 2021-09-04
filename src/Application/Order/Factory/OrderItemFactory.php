<?php

declare(strict_types=1);

namespace App\Application\Order\Factory;

use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;

class OrderItemFactory
{
    public function createOrderItem(OfferTicket $offerTicket): OrderItem
    {
        switch ($offerTicket) {
            case $offerTicket instanceof TicketOfferWithNumberOfDays:
              return $this->createTicketWithNumberOfDays($offerTicket);
            case $offerTicket instanceof TicketOfferWithNumberOfEntries:
                return $this->createTicketWithNumberOfEntries($offerTicket);
            default:
                throw new InvalidValueException('Invalid class');
        }
    }

    private function createTicketWithNumberOfDays(TicketOfferWithNumberOfDays $offerTicket): TicketWithNumberOfDays
    {
        return new TicketWithNumberOfDays(
            $offerTicket->getId(),
            $offerTicket->getPrice(),
            new NumberOfDays($offerTicket->getQuantity()->getValue())
        );
    }

    private function createTicketWithNumberOfEntries(TicketOfferWithNumberOfEntries $offerTicket): TicketWithNumberOfEntries
    {
        return new TicketWithNumberOfEntries(
            $offerTicket->getId(),
            $offerTicket->getPrice(),
            new NumberOfEntries($offerTicket->getQuantity()->getValue())
        );
    }
}
