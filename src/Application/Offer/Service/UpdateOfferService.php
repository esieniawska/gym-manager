<?php

declare(strict_types=1);

namespace App\Application\Offer\Service;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Dto\UpdateOfferDto;
use App\Application\Offer\Exception\InvalidOfferTypeException;
use App\Domain\Offer\Exception\InvalidOfferStatusException;
use App\Domain\Offer\Exception\OfferNotFoundException;
use App\Domain\Offer\Exception\OfferUpdateBlockedException;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\OfferWithNumberOfDays;
use App\Domain\Offer\Model\OfferWithNumberOfEntries;
use App\Domain\Offer\OfferFacade;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\PositiveValue;

class UpdateOfferService
{
    public function __construct(
        private OfferFacade $offerFacade,
        private OfferDtoAssembler $offerDtoAssembler,
        private OfferRepository $offerRepository
    ) {
    }

    /**
     * @throws InvalidOfferTypeException
     * @throws OfferNotFoundException
     * @throws OfferUpdateBlockedException
     */
    public function updateOffer(UpdateOfferDto $dto): OfferDto
    {
        $offer = $this->offerFacade->getOfferById($dto->getId());
        $this->updateFields($offer, $dto);
        $this->offerRepository->updateOffer($offer);

        return $this->offerDtoAssembler->assembleDomainObjectToDto($offer);
    }

    /**
     * @throws InvalidOfferTypeException
     * @throws OfferUpdateBlockedException
     */
    private function updateFields(OfferTicket $offer, UpdateOfferDto $dto): void
    {
        $offer->updatePrice(new Money($dto->getPrice()));
        $offer->updateOfferName(new OfferName($dto->getName()));
        $offer->updateQuantity($this->createQuantity($offer, $dto->getQuantity()));
    }

    private function createQuantity(OfferTicket $offerTicket, int $quantity): PositiveValue
    {
        switch ($offerTicket) {
            case $offerTicket instanceof OfferWithNumberOfDays:
                return new NumberOfDays($quantity);
            case $offerTicket instanceof OfferWithNumberOfEntries:
                return new NumberOfEntries($quantity);
            default:
                throw new InvalidOfferTypeException('Invalid offer type');
        }
    }

    /**
     * @throws OfferNotFoundException
     * @throws InvalidOfferStatusException
     */
    public function disableEditing(string $id): OfferDto
    {
        $offer = $this->offerFacade->getOfferById($id);
        $offer->disableEditing();
        $this->offerRepository->updateOfferStatus($offer);

        return $this->offerDtoAssembler->assembleDomainObjectToDto($offer);
    }

    /**
     * @throws OfferNotFoundException
     * @throws InvalidOfferStatusException
     */
    public function enableEditing(string $id): OfferDto
    {
        $offer = $this->offerFacade->getOfferById($id);
        $offer->enableEditing();
        $this->offerRepository->updateOfferStatus($offer);

        return $this->offerDtoAssembler->assembleDomainObjectToDto($offer);
    }
}
