<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

use App\Domain\User\Repository\UserRepository;

class EmailIsUniqueSpecification implements Specification
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function isSatisfiedBy(string $value): bool
    {
        $user = $this->userRepository->getByEmail($value);

        return null === $user;
    }
}
