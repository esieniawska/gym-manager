<?php

declare(strict_types=1);

namespace App\Application\Order\Specification;

use App\Domain\Client\Model\Client;
use App\Domain\Offer\Model\OfferTicket;

class OfferGenderIsCorrectSpecification implements OfferSpecification
{
    public function __construct(private Client $client)
    {
    }

    public function isSatisfiedBy(OfferTicket $offerTicket): bool
    {
        return $offerTicket->isAcceptedGender($this->client->getGender());
    }
}
