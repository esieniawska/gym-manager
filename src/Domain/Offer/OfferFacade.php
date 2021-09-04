<?php

declare(strict_types=1);

namespace App\Domain\Offer;

use App\Domain\Offer\Exception\OfferNotFoundException;
use App\Domain\Offer\Model\Filter;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

class OfferFacade
{
    public function __construct(private OfferRepository $offerRepository)
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
}
