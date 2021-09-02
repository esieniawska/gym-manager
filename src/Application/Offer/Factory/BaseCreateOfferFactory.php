<?php

declare(strict_types=1);

namespace App\Application\Offer\Factory;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\CreateNumberOfEntriesOfferDto;
use App\Application\Offer\Dto\CreateOfferDto;
use App\Application\Offer\Exception\InvalidOfferTypeException;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Repository\OfferRepository;

abstract class BaseCreateOfferFactory
{
    public function __construct(protected OfferRepository $repository)
    {
    }

    abstract protected function createTicketOfferWithNumberOfDays(CreateNumberOfDaysOfferDto $dto): OfferTicket;

    abstract protected function createTicketOfferWithNumberOfEntries(CreateNumberOfEntriesOfferDto $dto): OfferTicket;

    public function createOfferTicket(CreateOfferDto $dto): OfferTicket
    {
        switch ($dto) {
            case $dto instanceof CreateNumberOfDaysOfferDto:
                return $this->createTicketOfferWithNumberOfDays($dto);
            case $dto instanceof CreateNumberOfEntriesOfferDto:
                return $this->createTicketOfferWithNumberOfEntries($dto);
            default:
                throw new InvalidOfferTypeException('Invalid offer type');
        }
    }
}
