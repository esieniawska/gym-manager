<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Repository;

use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Offer\Converter\DbOfferConverter;
use App\Infrastructure\Offer\Entity\DbOffer;
use App\Infrastructure\Offer\Exception\OfferNotFoundException;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOfferRepository extends DoctrineRepository implements OfferRepository
{
    public function __construct(ManagerRegistry $registry, DbOfferConverter $clientConverter)
    {
        parent::__construct($registry, DbOffer::class, $clientConverter);
    }

    public function getOfferById(Uuid $id): ?OfferTicket
    {
        $dbOffer = $this->getRepository()->find((string) $id);

        return null === $dbOffer ? null : $this->converter->convertDbModelToDomainObject($dbOffer);
    }

    public function addOffer(OfferTicket $offerTicket): void
    {
        $this->addEntity($offerTicket);
    }

    public function getAll(): ArrayCollection
    {
        $dbOffers = $this->getRepository()->findAll();

        return $this->converter->convertAllDbModelToDomainObject($dbOffers);
    }

    public function updateOffer(OfferTicket $offerTicket): void
    {
        $dbOffer = $this->getRepository()->find((string) $offerTicket->getId());

        if (null === $dbOffer) {
            throw new OfferNotFoundException();
        }

        $this->updateDbOfferFields($offerTicket, $dbOffer);
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    private function updateDbOfferFields(OfferTicket $offerTicket, DbOffer $dbOffer)
    {
        $dbOffer->setPrice($offerTicket->getPrice()->getPrice());
        $dbOffer->setName((string) $offerTicket->getName());
        $dbOffer->setQuantity($offerTicket->getQuantity()->getValue());
    }
}
