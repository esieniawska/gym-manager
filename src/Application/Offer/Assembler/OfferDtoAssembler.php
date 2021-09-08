<?php

declare(strict_types=1);

namespace App\Application\Offer\Assembler;

use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Exception\InvalidOfferTypeException;
use App\Application\Shared\Assembler\DtoCollectionAssembler;
use App\Domain\Offer\Model\GenderOfferTicket;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\OfferWithNumberOfDays;
use App\Domain\Offer\Model\OfferWithNumberOfEntries;
use App\Domain\Shared\Model\DomainModel;

class OfferDtoAssembler extends DtoCollectionAssembler
{
    public function assembleDomainObjectToDto(DomainModel $domainModel): OfferDto
    {
        /* @var $domainModel OfferTicket */
        return new OfferDto(
            (string) $domainModel->getId(),
            $this->getType($domainModel),
            (string) $domainModel->getName(),
            $domainModel->getPrice()->getFloatValue(),
            (string) $domainModel->getStatus(),
            $domainModel->getQuantity()->getValue(),
            $this->getGender($domainModel)
        );
    }

    private function getGender(OfferTicket $offerTicket): ?string
    {
        return $offerTicket instanceof GenderOfferTicket
            ? (string) $offerTicket->getGender()
            : null;
    }

    private function getType(OfferTicket $offerTicket): string
    {
        switch ($offerTicket) {
            case $offerTicket instanceof OfferWithNumberOfDays:
                return OfferDto::TYPE_NUMBER_OF_DAYS;
            case $offerTicket instanceof OfferWithNumberOfEntries:
                return OfferDto::TYPE_NUMBER_OF_ENTRIES;
            default:
                throw new InvalidOfferTypeException('Invalid offer type');
        }
    }
}
