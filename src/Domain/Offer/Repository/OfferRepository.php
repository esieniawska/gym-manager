<?php

namespace App\Domain\Offer\Repository;

use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Shared\ValueObject\Uuid;

interface OfferRepository
{
    public function getOfferById(Uuid $id): ?OfferTicket;
}