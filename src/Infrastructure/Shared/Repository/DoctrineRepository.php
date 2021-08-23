<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class DoctrineRepository extends ServiceEntityRepository
{
    private string $entityClass;

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
        $this->entityClass = $entityClass;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->_em;
    }

    protected function getRepository(): EntityRepository
    {
        return $this->getEntityManager()->getRepository($this->entityClass);
    }
}
