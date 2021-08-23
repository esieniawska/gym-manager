<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Service;

use App\Application\User\Service\PasswordEncoder;
use App\Domain\User\Entity\Password;
use App\Domain\User\Entity\PasswordHash;

class ArgonPasswordEncoder implements PasswordEncoder
{
    public function encode(Password $password): PasswordHash
    {
        return new PasswordHash(password_hash($password->getValue(), PASSWORD_ARGON2I));
    }

    public function isValid(Password $password, PasswordHash $currentPasswordHash): bool
    {
        return password_verify($password->getValue(), $currentPasswordHash->getValue());
    }
}
