<?php

namespace App\Application\Order\Specification;

use App\Application\Offer\Dto\OfferDto;

interface OfferSpecification
{
    public function isSatisfiedBy(OfferDto $offerTicket): bool;
}
