<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Factory;

use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Offer\Entity\DbOffer;

class OfferFactory extends BaseOfferFactory
{
    protected function createTicketOfferWithNumberOfEntries(DbOffer $dbOffer): TicketOfferWithNumberOfEntries
    {
        return new TicketOfferWithNumberOfEntries(
            new Uuid($dbOffer->getId()->toString()),
            new OfferName($dbOffer->getName()),
            new Money($dbOffer->getPrice()),
            $dbOffer->getStatus(),
            new NumberOfEntries($dbOffer->getQuantity()),
        );
    }

    protected function createTicketOfferWithNumberOfDays(DbOffer $dbOffer): TicketOfferWithNumberOfDays
    {
        return new TicketOfferWithNumberOfDays(
            new Uuid($dbOffer->getId()->toString()),
            new OfferName($dbOffer->getName()),
            new Money($dbOffer->getPrice()),
            $dbOffer->getStatus(),
            new NumberOfDays($dbOffer->getQuantity())
        );
    }
}
