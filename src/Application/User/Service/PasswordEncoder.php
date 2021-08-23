<?php

namespace App\Application\User\Service;

use App\Domain\User\Entity\Password;
use App\Domain\User\Entity\PasswordHash;

interface PasswordEncoder
{
    public function encode(Password $password): PasswordHash;

    public function isValid(Password $password, PasswordHash $currentPasswordHash): bool;
}
