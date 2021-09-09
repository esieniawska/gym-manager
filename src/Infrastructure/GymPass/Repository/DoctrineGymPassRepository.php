<?php

declare(strict_types=1);

namespace App\Infrastructure\GymPass\Repository;

use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\GymPass\Converter\DbGymPassConverter;
use App\Infrastructure\GymPass\Entity\DbGymEntering;
use App\Infrastructure\GymPass\Entity\DbGymPass;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid as RamseyUuid;

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
        $gymPass = $this->getRepository()->find((string) $id);

        return null === $gymPass ? null : $this->converter->convertDbModelToDomainObject($gymPass);
    }

    public function addLastGymPassEntering(GymPass $gymPass): void
    {
        /** @var $dbGymPass DbGymPass */
        $dbGymPass = $this->getRepository()->find($gymPass->getId());
        $gymEntries = $gymPass->getGymEntering();
        $newGymEntering = end($gymEntries);
        $dbGymEntering = new DbGymEntering(
            RamseyUuid::uuid4(),
            $dbGymPass,
            $newGymEntering->getDate()
        );
        $dbGymPass->addGymEntering(
            $dbGymEntering
        );
        $this->getEntityManager()->flush();
    }

    public function updateGymPassDates(GymPass $gymPass): void
    {
        // TODO: Implement updateGymPassDates() method.
    }
}
