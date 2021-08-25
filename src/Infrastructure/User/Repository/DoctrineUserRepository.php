<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use App\Infrastructure\User\Converter\UserDbConverter;
use App\Infrastructure\User\Entity\DbUser;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry, UserDbConverter $clientConverter)
    {
        parent::__construct($registry, DbUser::class, $clientConverter);
    }

    public function addUser(User $user): void
    {
        $this->addEntity($user);
    }

    public function getByEmail(string $email): ?User
    {
        $dbUser = $this->getRepository()->findOneBy(['email' => $email]);

        return null === $dbUser ? null : $this->converter->convertDbModelToDomainObject($dbUser);
    }
}
