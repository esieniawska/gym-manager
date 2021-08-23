<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

class RegisterUserDto
{
    public function __construct(
        private string $email,
        private string $firstName,
        private string $lastName,
        private string $password
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
