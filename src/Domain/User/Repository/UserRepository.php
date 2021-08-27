<?php

namespace App\Domain\User\Repository;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Model\User;

interface UserRepository
{
    public function nextIdentity(): Uuid;

    public function addUser(User $user): void;

    public function getByEmail(string $email): ?User;
}
