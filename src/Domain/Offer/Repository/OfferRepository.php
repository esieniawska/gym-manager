<?php

namespace App\Domain\Offer\Repository;

use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Shared\ValueObject\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

interface OfferRepository
{
    public function getOfferById(Uuid $id): ?OfferTicket;

    public function addOffer(OfferTicket $offerTicket): void;

    public function nextIdentity(): Uuid;

    public function getAll(): ArrayCollection;

    public function updateOffer(OfferTicket $offerTicket): void;

    public function updateOfferStatus(OfferTicket $offerTicket): void;
}
