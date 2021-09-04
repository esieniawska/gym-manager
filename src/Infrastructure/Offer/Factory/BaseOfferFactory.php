<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Factory;

use App\Application\Offer\Exception\InvalidOfferTypeException;
use App\Domain\Offer\Model\OfferTicket;
use App\Infrastructure\Offer\Entity\DbOffer;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;

abstract class BaseOfferFactory
{
    abstract protected function createTicketOfferWithNumberOfDays(DbOffer $dbOffer): OfferTicket;

    abstract protected function createTicketOfferWithNumberOfEntries(DbOffer $dbOffer): OfferTicket;

    public function createOfferTicket(DbOffer $dbOffer): OfferTicket
    {
        switch ($dbOffer->getType()) {
            case OfferTypeEnum::TYPE_NUMBER_OF_DAYS():
                return $this->createTicketOfferWithNumberOfDays($dbOffer);
            case OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES():
                return $this->createTicketOfferWithNumberOfEntries($dbOffer);
            default:
                throw new InvalidOfferTypeException('Invalid offer type');
        }
    }
}
