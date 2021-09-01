<?php

declare(strict_types=1);

namespace App\Infrastructure\GymPass\Repository;

use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\GymPass\Converter\GymPassDbConverter;
use App\Infrastructure\Shared\Entity\DbEntity;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineGymPassRepository extends DoctrineRepository implements GymPassRepository
{
    public function __construct(ManagerRegistry $registry, GymPassDbConverter $clientConverter)
    {
        parent::__construct($registry, DbEntity::class, $clientConverter);
    }

    public function addGymPass(GymPass $gymPass): void
    {
        // TODO: Implement addGymPass() method.
    }

    public function getGymPass(Uuid $id): ?GymPass
    {
        // TODO: Implement getGymPass() method.
    }

    public function updateGymPassEntries(GymPass $gymPass): void
    {
        // TODO: Implement updateGymPassEntries() method.
    }

    public function updateGymPassDates(GymPass $gymPass): void
    {
        // TODO: Implement updateGymPassDates() method.
    }
}
