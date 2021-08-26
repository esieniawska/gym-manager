<?php

namespace App\Application\Client\Dto;

use App\Application\Shared\Dto\BaseDto;

class ClientDto implements BaseDto
{
    public function __construct(
        private string $id,
        private string $cardNumber,
        private string $status,
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
