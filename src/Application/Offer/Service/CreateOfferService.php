<?php

declare(strict_types=1);

namespace App\Application\Offer\Service;

use App\Application\Offer\Dto\CreateOfferDto;
use App\Application\Offer\Factory\OfferFactory;
use App\Domain\Offer\Repository\OfferRepository;

class CreateOfferService
{
    public function __construct(private OfferRepository $offerRepository, private OfferFactory $offerFactory)
    {
    }

    public function create(CreateOfferDto $dto): void
    {
        $offerTicket = $this->offerFactory->createOfferTicket($dto);
        $this->offerRepository->addOffer($offerTicket);
    }
}
