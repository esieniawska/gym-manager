<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\Factory;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\CreateNumberOfEntriesOfferDto;
use App\Application\Offer\Dto\CreateOfferDto;
use App\UI\Offer\Exception\InvalidOfferTypeException;
use App\UI\Offer\Http\Dto\OfferDto;
use App\UI\Offer\Http\Dto\OfferType;

class CreateOfferDtoFactory
{
    public function createDtoFromHttp(OfferDto $dto): CreateOfferDto
    {
        switch ($dto->getType()) {
            case OfferType::TYPE_NUMBER_OF_DAYS:
                return $this->createNumberOfDaysOfferDto($dto);
            case OfferType::TYPE_NUMBER_OF_ENTRIES:
                return $this->createNumberOfEntriesOfferDto($dto);
            default:
                throw new InvalidOfferTypeException('Invalid offer type');
        }
    }

    private function createNumberOfDaysOfferDto(OfferDto $dto): CreateNumberOfDaysOfferDto
    {
        return new CreateNumberOfDaysOfferDto(
            $dto->getName(),
            $dto->getPrice(),
            $dto->getQuantity(),
            $dto->getGender()
        );
    }

    private function createNumberOfEntriesOfferDto(OfferDto $dto): CreateNumberOfEntriesOfferDto
    {
        return new CreateNumberOfEntriesOfferDto(
            $dto->getName(),
            $dto->getPrice(),
            $dto->getQuantity(),
            $dto->getGender()
        );
    }
}
