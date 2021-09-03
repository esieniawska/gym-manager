<?php

declare(strict_types=1);

namespace App\UI\Offer\Converter;

use App\Application\Shared\Dto\BaseDto as ApplicationDto;
use App\UI\Offer\Http\Dto\OfferDto;
use App\UI\Shared\Converter\DtoCollectionConverter;

class OfferDtoConverter extends DtoCollectionConverter
{
    public function createHttpFromApplicationDto(ApplicationDto $dto): OfferDto
    {
        return (new OfferDto())
            ->setId($dto->getId())
            ->setType($dto->getType())
            ->setQuantity($dto->getQuantity())
            ->setPrice($dto->getPrice())
            ->setName($dto->getName())
            ->setGender($dto->getGender())
            ->setStatus($dto->getStatus());
    }
}
