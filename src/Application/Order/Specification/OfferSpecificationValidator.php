<?php

declare(strict_types=1);

namespace App\Application\Order\Specification;

use App\Application\Offer\Dto\OfferDto;
use App\Application\Order\Exception\OrderFailedException;

class OfferSpecificationValidator
{
    public function __construct(private OfferSpecification $specification, private string $errorMessage)
    {
    }

    public function checkIsValidOffer(OfferDto $offerTicket): void
    {
        if (!$this->specification->isSatisfiedBy($offerTicket)) {
            throw new OrderFailedException($this->errorMessage);
        }
    }
}
