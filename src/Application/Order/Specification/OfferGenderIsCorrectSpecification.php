<?php

declare(strict_types=1);

namespace App\Application\Order\Specification;

use App\Application\Client\Dto\ClientDto;
use App\Application\Offer\Dto\OfferDto;

class OfferGenderIsCorrectSpecification implements OfferSpecification
{
    public function __construct(private ClientDto $client)
    {
    }

    public function isSatisfiedBy(OfferDto $offerTicket): bool
    {
        return $this->client->getGender() === $offerTicket->getGender();
    }
}
