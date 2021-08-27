<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Repository;

use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Shared\Converter\DbConverter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid as RamseyUuid;

abstract class DoctrineRepository extends ServiceEntityRepository
{
    private string $entityClass;
    protected DbConverter $converter;

    public function __construct(ManagerRegistry $registry, string $entityClass, DbConverter $converter)
    {
        parent::__construct($registry, $entityClass);
        $this->entityClass = $entityClass;
        $this->converter = $converter;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->_em;
    }

    protected function getRepository(): EntityRepository
    {
        return $this->getEntityManager()->getRepository($this->entityClass);
    }

    public function nextIdentity(): Uuid
    {
        return new Uuid(RamseyUuid::uuid4()->toString());
    }

    public function addEntity(DomainModel $domainModel): void
    {
        $dbModel = $this->converter->convertDomainObjectToDbModel($domainModel);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($dbModel);
        $entityManager->flush();
    }
}
