<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\WrongEmailAddressException;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use App\Infrastructure\User\Converter\UserConverter;
use App\Infrastructure\User\Entity\DbUser;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry, private UserConverter $userConverter)
    {
        parent::__construct($registry, DbUser::class);
    }

    public function addUser(User $user): void
    {
        $dbModel = $this->userConverter->convertDomainObjectToDbModel($user);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($dbModel);
        $entityManager->flush();
    }

    /**
     * @throws WrongEmailAddressException
     */
    public function getByEmail(string $email): ?User
    {
        $dbUser = $this->getRepository()->findOneBy(['email' => $email]);

        return null === $dbUser ? null : $this->userConverter->convertDbModelToDomainObject($dbUser);
    }
}
