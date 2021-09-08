<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Repository;

use App\Domain\Offer\Model\Filter;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Offer\Converter\DbOfferConverter;
use App\Infrastructure\Offer\Entity\DbOffer;
use App\Infrastructure\Offer\Exception\OfferNotFoundException;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
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

    public function getAll(Filter $filter): ArrayCollection
    {
        $dbOffers = $this->findAllOffers($filter);

        return $this->converter->convertAllDbModelToDomainObject($dbOffers);
    }

    private function findAllOffers(Filter $filter): array
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getRepository()->createQueryBuilder('offer');
        $queryBuilder
            ->select('offer');

        if (null !== $filter->getName()) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('LOWER(offer.name)', ':name'))
                ->setParameter(':name', '%'.strtolower($filter->getName()).'%');
        }

        if (null !== $filter->getStatus()) {
            $queryBuilder
                ->andWhere('offer.status = :status')
                ->setParameter(':status', $filter->getStatus());
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function updateOffer(OfferTicket $offerTicket): void
    {
        $dbOffer = $this->getDbOffer($offerTicket);
        $this->updateDbOfferFields($offerTicket, $dbOffer);
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    private function getDbOffer(OfferTicket $offerTicket): DbOffer
    {
        $dbOffer = $this->getRepository()->find((string) $offerTicket->getId());

        if (null === $dbOffer) {
            throw new OfferNotFoundException();
        }

        return $dbOffer;
    }

    private function updateDbOfferFields(OfferTicket $offerTicket, DbOffer $dbOffer)
    {
        $dbOffer->setPrice($offerTicket->getPrice()->getIntValue());
        $dbOffer->setName((string) $offerTicket->getName());
        $dbOffer->setQuantity($offerTicket->getQuantity()->getValue());
    }

    public function updateOfferStatus(OfferTicket $offerTicket): void
    {
        $dbOffer = $this->getDbOffer($offerTicket);
        $dbOffer->setStatus($offerTicket->getStatus());
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }
}
