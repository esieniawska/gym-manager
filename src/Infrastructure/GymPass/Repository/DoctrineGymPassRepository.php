<?php

declare(strict_types=1);

namespace App\Infrastructure\GymPass\Repository;

use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\GymPass\Converter\DbGymPassConverter;
use App\Infrastructure\GymPass\Entity\DbGymEntering;
use App\Infrastructure\GymPass\Entity\DbGymPass;
use App\Infrastructure\GymPass\Entity\DbGymPassWithEndDate;
use App\Infrastructure\GymPass\Exception\GymPassNotFoundException;
use App\Infrastructure\GymPass\Exception\InvalidGymPassTypeException;
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

    /**
     * @throws GymPassNotFoundException
     */
    public function addLastGymPassEntering(GymPass $gymPass): void
    {
        $dbGymPass = $this->getDbGymPassById($gymPass->getId()->getValue());
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

    /**
     * @throws GymPassNotFoundException
     */
    public function updateGymPassDates(GymPassWithEndDate $gymPass): void
    {
        $dbGymPass = $this->getDbGymPassById($gymPass->getId()->getValue());

        if (!$dbGymPass instanceof DbGymPassWithEndDate) {
            throw new InvalidGymPassTypeException('Invalid gym pass type.');
        }
        $dbGymPass->setEndDate($gymPass->getEndDate());
        $dbGymPass->setLockStartDate($gymPass->getLockStartDate());
        $dbGymPass->setLockEndDate($gymPass->getLockEndDate());
        $this->getEntityManager()->flush();
    }

    private function getDbGymPassById(string $id): DbGymPass
    {
        $dbGymPass = $this->getRepository()->find($id);

        if (null === $dbGymPass) {
            throw new GymPassNotFoundException();
        }

        return $dbGymPass;
    }
}
