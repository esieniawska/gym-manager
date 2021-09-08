<?php

declare(strict_types=1);

namespace App\Application\Offer;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Exception\OfferCanNotBeOrderedException;
use App\Application\Offer\Exception\OfferNotFoundException;
use App\Domain\Offer\Model\Filter;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

class OfferFacade
{
    public function __construct(private OfferRepository $offerRepository, private OfferDtoAssembler $offerDtoAssembler)
    {
    }

    /**
     * @throws OfferNotFoundException
     */
    public function getOfferById(string $id): OfferTicket
    {
        $offer = $this->offerRepository->getOfferById(new Uuid($id));

        if (null === $offer) {
            throw new OfferNotFoundException('Offer not found');
        }

        return $offer;
    }

    public function getAllOffers(Filter $filter): ArrayCollection
    {
        return $this->offerRepository->getAll($filter);
    }

    /**
     * @throws OfferNotFoundException
     * @throws OfferCanNotBeOrderedException
     */
    public function getOfferByIdThatCanBeOrdered(string $id): OfferDto
    {
        $offer = $this->getOfferById($id);

        if (!$offer->canBeOrdered()) {
            throw new OfferCanNotBeOrderedException('Offer can\'t be ordered.');
        }

        return $this->offerDtoAssembler->assembleDomainObjectToDto($offer);
    }
}
