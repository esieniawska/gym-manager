<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Factory;

use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Offer\Entity\DbOffer;

class OfferWithGenderFactory extends BaseOfferFactory
{
    protected function createTicketOfferWithNumberOfDays(DbOffer $dbOffer): TicketOfferWithNumberOfDaysAndGender
    {
        return new TicketOfferWithNumberOfDaysAndGender(
            new Uuid($dbOffer->getId()->toString()),
            new OfferName($dbOffer->getName()),
            new Money($dbOffer->getPrice()),
            $dbOffer->getStatus(),
            new NumberOfDays($dbOffer->getQuantity()),
            $dbOffer->getGender()
        );
    }

    protected function createTicketOfferWithNumberOfEntries(DbOffer $dbOffer): TicketOfferWithNumberOfEntriesAndGender
    {
        return new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid($dbOffer->getId()->toString()),
            new OfferName($dbOffer->getName()),
            new Money($dbOffer->getPrice()),
            $dbOffer->getStatus(),
            new NumberOfEntries($dbOffer->getQuantity()),
            $dbOffer->getGender()
        );
    }
}
