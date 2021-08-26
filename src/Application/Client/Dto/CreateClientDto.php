<?php

namespace App\Application\Client\Dto;

use App\Application\Shared\Dto\BaseDto;

class CreateClientDto implements BaseDto
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $gender,
        private ?string $phoneNumber,
        private ?string $email
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
