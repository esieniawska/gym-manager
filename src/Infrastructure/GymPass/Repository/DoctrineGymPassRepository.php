<?php

declare(strict_types=1);

namespace App\Infrastructure\GymPass\Repository;

use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\GymPass\Converter\DbGymPassConverter;
use App\Infrastructure\GymPass\Entity\DbGymPass;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineGymPassRepository extends DoctrineRepository implements GymPassRepository
{
    public function __construct(ManagerRegistry $registry, DbGymPassConverter $clientConverter)
    {
        parent::__construct($registry, DbGymPass::class, $clientConverter);
    }

    public function addGymPass(GymPass $gymPass): void
    {
        $this->addEntity($gymPass);
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
