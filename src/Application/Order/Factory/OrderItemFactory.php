<?php

declare(strict_types=1);

namespace App\Application\Order\Factory;

use App\Application\Offer\Dto\OfferDto;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class OrderItemFactory
{
    public function createOrderItem(OfferDto $offerTicket): OrderItem
    {
        switch ($offerTicket->getType()) {
            case OfferDto::TYPE_NUMBER_OF_DAYS:
              return $this->createTicketWithNumberOfDays($offerTicket);
            case OfferDto::TYPE_NUMBER_OF_ENTRIES:
                return $this->createTicketWithNumberOfEntries($offerTicket);
            default:
                throw new InvalidValueException('Invalid class');
        }
    }

    private function createTicketWithNumberOfDays(OfferDto $offerTicket): TicketWithNumberOfDays
    {
        return new TicketWithNumberOfDays(
           new Uuid($offerTicket->getId()),
           new Money($offerTicket->getPrice()),
            new NumberOfDays($offerTicket->getQuantity())
        );
    }

    private function createTicketWithNumberOfEntries(OfferDto $offerTicket): TicketWithNumberOfEntries
    {
        return new TicketWithNumberOfEntries(
            new Uuid($offerTicket->getId()),
            new Money($offerTicket->getPrice()),
            new NumberOfEntries($offerTicket->getQuantity())
        );
    }
}
