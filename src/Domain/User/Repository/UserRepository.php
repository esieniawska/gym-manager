<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface UserRepository
{
    public function addUser(User $user): void;

    public function getByEmail(string $email): ?User;
}
