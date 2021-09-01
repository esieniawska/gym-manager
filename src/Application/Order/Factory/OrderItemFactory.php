<?php

declare(strict_types=1);

namespace App\Application\Order\Factory;

use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Offer\OfferFacade;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Model\TicketStatus;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Order\Model\TicketWithNumberOfEntriesAndGender;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;

class OrderItemFactory
{
    public function __construct(private OfferFacade $offerFacade)
    {
    }

    public function createOrderItemFromOfferId(string $id): OrderItem
    {
        $offerTicket = $this->offerFacade->getOfferById($id);

        switch ($offerTicket) {
            case $offerTicket instanceof TicketOfferWithNumberOfDays:
              return $this->createTicketWithNumberOfDays($offerTicket);
            case $offerTicket instanceof TicketOfferWithNumberOfEntries:
                return $this->createTicketWithNumberOfEntries($offerTicket);
            case $offerTicket instanceof TicketOfferWithNumberOfDaysAndGender:
                return $this->createTicketWithNumberOfDaysAndGender($offerTicket);
            case $offerTicket instanceof TicketOfferWithNumberOfEntriesAndGender:
                return $this->createTicketWithNumberOfEntriesAndGender($offerTicket);
            default:
                throw new InvalidValueException('Invalid class');
        }
    }

    private function createTicketWithNumberOfDays(TicketOfferWithNumberOfDays $offerTicket): TicketWithNumberOfDays
    {
        return new TicketWithNumberOfDays(
            $offerTicket->getId(),
            $offerTicket->getPrice(),
            new TicketStatus((string) $offerTicket->getStatus()),
            new NumberOfDays($offerTicket->getNumberOfDays()->getValue())
        );
    }

    private function createTicketWithNumberOfEntries(TicketOfferWithNumberOfEntries $offerTicket): TicketWithNumberOfEntries
    {
        return new TicketWithNumberOfEntries(
            $offerTicket->getId(),
            $offerTicket->getPrice(),
            new TicketStatus((string) $offerTicket->getStatus()),
            new NumberOfEntries($offerTicket->getNumberOfEntries()->getValue())
        );
    }

    private function createTicketWithNumberOfDaysAndGender(TicketOfferWithNumberOfDaysAndGender $offerTicket): TicketWithNumberOfDaysAndGender
    {
        return new TicketWithNumberOfDaysAndGender(
            $offerTicket->getId(),
            $offerTicket->getPrice(),
            new TicketStatus((string) $offerTicket->getStatus()),
            new NumberOfDays($offerTicket->getNumberOfDays()->getValue()),
            $offerTicket->getGender()
        );
    }

    private function createTicketWithNumberOfEntriesAndGender(TicketOfferWithNumberOfEntriesAndGender $offerTicket): TicketWithNumberOfEntriesAndGender
    {
        return new TicketWithNumberOfEntriesAndGender(
            $offerTicket->getId(),
            $offerTicket->getPrice(),
            new TicketStatus((string) $offerTicket->getStatus()),
            new NumberOfEntries($offerTicket->getNumberOfEntries()->getValue()),
            $offerTicket->getGender()
        );
    }
}
