<?php

declare(strict_types=1);

namespace App\Application\Order\Factory;

use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\OfferWithNumberOfDays;
use App\Domain\Offer\Model\OfferWithNumberOfEntries;
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
            case $offerTicket instanceof OfferWithNumberOfDays:
              return $this->createTicketWithNumberOfDays($offerTicket);
            case $offerTicket instanceof OfferWithNumberOfEntries:
                return $this->createTicketWithNumberOfEntries($offerTicket);
            default:
                throw new InvalidValueException('Invalid class');
        }
    }

    private function createTicketWithNumberOfDays(OfferWithNumberOfDays $offerTicket): TicketWithNumberOfDays
    {
        return new TicketWithNumberOfDays(
            $offerTicket->getId(),
            $offerTicket->getPrice(),
            new NumberOfDays($offerTicket->getQuantity()->getValue())
        );
    }

    private function createTicketWithNumberOfEntries(OfferWithNumberOfEntries $offerTicket): TicketWithNumberOfEntries
    {
        return new TicketWithNumberOfEntries(
            $offerTicket->getId(),
            $offerTicket->getPrice(),
            new NumberOfEntries($offerTicket->getQuantity()->getValue())
        );
    }
}
