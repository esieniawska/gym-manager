<?php

declare(strict_types=1);

namespace App\Application\Offer\Factory;

use App\Application\Offer\Dto\CreateOfferDto;
use App\Application\Offer\Exception\InvalidOfferTypeException;
use App\Domain\Offer\Model\OfferTicket;

class OfferFactory
{
    public function __construct(
        private CreateOfferWithGenderFactory $createOfferWithGenderFactory,
        private CreateOfferFactory $createOfferFactory
    ) {
    }

    /**
     * @throws InvalidOfferTypeException
     */
    public function createOfferTicket(CreateOfferDto $dto): OfferTicket
    {
        return null === $dto->getGender()
            ? $this->createOfferFactory->createOfferTicket($dto)
            : $this->createOfferWithGenderFactory->createOfferTicket($dto);
    }
}
