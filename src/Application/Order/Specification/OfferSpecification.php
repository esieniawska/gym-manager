<?php

namespace App\Application\Order\Specification;

use App\Domain\Offer\Model\OfferTicket;

interface OfferSpecification
{
    public function isSatisfiedBy(OfferTicket $offerTicket): bool;
}
