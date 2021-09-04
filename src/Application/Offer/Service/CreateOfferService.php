<?php

declare(strict_types=1);

namespace App\Application\Offer\Service;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\CreateOfferDto;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Factory\OfferFactory;
use App\Domain\Offer\Repository\OfferRepository;

class CreateOfferService
{
    public function __construct(
        private OfferRepository $offerRepository,
        private OfferFactory $offerFactory,
        private OfferDtoAssembler $assembler
    ) {
    }

    public function create(CreateOfferDto $dto): OfferDto
    {
        $offerTicket = $this->offerFactory->createOfferTicket($dto);
        $this->offerRepository->addOffer($offerTicket);

        return $this->assembler->assembleDomainObjectToDto($offerTicket);
    }
}
