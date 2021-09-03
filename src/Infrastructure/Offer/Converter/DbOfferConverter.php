<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Converter;

use App\Domain\Offer\Model\GenderOfferTicket;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\OfferWithNumberOfDays;
use App\Domain\Offer\Model\OfferWithNumberOfEntries;
use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\Gender;
use App\Infrastructure\Offer\Entity\DbOffer;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Offer\Exception\InvalidOfferTypeException;
use App\Infrastructure\Offer\Factory\OfferFactory;
use App\Infrastructure\Offer\Factory\OfferWithGenderFactory;
use App\Infrastructure\Shared\Converter\DbCollectionConverter;
use App\Infrastructure\Shared\Entity\DbEntity;
use Ramsey\Uuid\Uuid as RamseyUuid;

class DbOfferConverter extends DbCollectionConverter
{
    public function __construct(
        private OfferWithGenderFactory $offerWithGenderFactory,
        private OfferFactory $offerFactory
    ) {
    }

    public function convertDomainObjectToDbModel(DomainModel $offerTicket): DbOffer
    {
        /* @var $offerTicket OfferTicket */
        return new DbOffer(
            RamseyUuid::fromString((string) $offerTicket->getId()),
            (string) $offerTicket->getName(),
            $offerTicket->getStatus(),
            $this->getDbType($offerTicket),
            $offerTicket->getPrice()->getPrice(),
            $offerTicket->getQuantity()->getValue(),
            $this->getGenderFromDomainModel($offerTicket)
        );
    }

    private function getDbType(OfferTicket $offerTicket): OfferTypeEnum
    {
        switch ($offerTicket) {
            case $offerTicket instanceof OfferWithNumberOfDays:
                return OfferTypeEnum::TYPE_NUMBER_OF_DAYS();
            case $offerTicket instanceof OfferWithNumberOfEntries:
                return OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES();
            default:
                throw new InvalidOfferTypeException('Invalid offer type');
        }
    }

    private function getGenderFromDomainModel(OfferTicket $offerTicket): ?Gender
    {
        return $offerTicket instanceof GenderOfferTicket
            ? $offerTicket->getGender()
            : null;
    }

    public function convertDbModelToDomainObject(DbEntity $dbEntity): OfferTicket
    {
        return null === $dbEntity->getGender()
            ? $this->offerFactory->createOfferTicket($dbEntity)
            : $this->offerWithGenderFactory->createOfferTicket($dbEntity);
    }
}
