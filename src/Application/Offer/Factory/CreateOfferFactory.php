<?php

declare(strict_types=1);

namespace App\Application\Offer\Factory;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\CreateNumberOfEntriesOfferDto;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;

class CreateOfferFactory extends BaseCreateOfferFactory
{
    protected function createTicketOfferWithNumberOfEntries(CreateNumberOfEntriesOfferDto $dto): TicketOfferWithNumberOfEntries
    {
        return new TicketOfferWithNumberOfEntries(
            $this->repository->nextIdentity(),
            new OfferName($dto->getName()),
            new Money($dto->getPrice()),
            OfferStatus::ACTIVE(),
            new NumberOfEntries($dto->getQuantity()),
        );
    }

    protected function createTicketOfferWithNumberOfDays(CreateNumberOfDaysOfferDto $dto): TicketOfferWithNumberOfDays
    {
        return new TicketOfferWithNumberOfDays(
            $this->repository->nextIdentity(),
            new OfferName($dto->getName()),
            new Money($dto->getPrice()),
            OfferStatus::ACTIVE(),
            new NumberOfDays($dto->getQuantity())
        );
    }
}
