<?php

declare(strict_types=1);

namespace App\Application\Offer\Factory;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\CreateNumberOfEntriesOfferDto;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;

class CreateOfferWithGenderFactory extends BaseCreateOfferFactory
{
    protected function createTicketOfferWithNumberOfDays(CreateNumberOfDaysOfferDto $dto): TicketOfferWithNumberOfDaysAndGender
    {
        return new TicketOfferWithNumberOfDaysAndGender(
            $this->repository->nextIdentity(),
            new OfferName($dto->getName()),
            new Money($dto->getPrice()),
            OfferStatus::ACTIVE(),
            new NumberOfDays($dto->getQuantity()),
            Gender::fromString($dto->getGender())
        );
    }

    protected function createTicketOfferWithNumberOfEntries(CreateNumberOfEntriesOfferDto $dto): TicketOfferWithNumberOfEntriesAndGender
    {
        return new TicketOfferWithNumberOfEntriesAndGender(
            $this->repository->nextIdentity(),
            new OfferName($dto->getName()),
            new Money($dto->getPrice()),
            OfferStatus::ACTIVE(),
            new NumberOfEntries($dto->getQuantity()),
            Gender::fromString($dto->getGender())
        );
    }
}
