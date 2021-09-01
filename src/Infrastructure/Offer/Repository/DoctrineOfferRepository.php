<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Repository;

use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Offer\Converter\DbOfferConverter;
use App\Infrastructure\Shared\Entity\DbEntity;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOfferRepository extends DoctrineRepository implements OfferRepository
{
    public function __construct(ManagerRegistry $registry, DbOfferConverter $clientConverter)
    {
        parent::__construct($registry, DbEntity::class, $clientConverter);
    }

    public function getOfferById(Uuid $id): ?OfferTicket
    {
        // TODO: Implement getOfferById() method.
    }
}