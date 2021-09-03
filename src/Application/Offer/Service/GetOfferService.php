<?php

declare(strict_types=1);

namespace App\Application\Offer\Service;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\OfferDto;
use App\Domain\Offer\Exception\OfferNotFoundException;
use App\Domain\Offer\OfferFacade;

class GetOfferService
{
    public function __construct(private OfferFacade $offerFacade, private OfferDtoAssembler $offerDtoAssembler)
    {
    }

    /**
     * @throws OfferNotFoundException
     */
    public function getOfferById(string $id): OfferDto
    {
        $offer = $this->offerFacade->getOfferById($id);

        return $this->offerDtoAssembler->assembleDomainObjectToDto($offer);
    }
}
