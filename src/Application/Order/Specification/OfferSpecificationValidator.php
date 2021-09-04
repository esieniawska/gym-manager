<?php

declare(strict_types=1);

namespace App\Application\Order\Specification;

use App\Application\Order\Exception\OrderFailedException;
use App\Domain\Offer\Model\OfferTicket;

class OfferSpecificationValidator
{
    public function __construct(private OfferSpecification $specification, private string $errorMessage)
    {
    }

    public function checkIsValidOffer(OfferTicket $offerTicket): void
    {
        if (!$this->specification->isSatisfiedBy($offerTicket)) {
            throw new OrderFailedException($this->errorMessage);
        }
    }
}
