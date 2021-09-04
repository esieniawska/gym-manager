<?php

declare(strict_types=1);

namespace App\Application\Client\Dto;

class Filter
{
    public function __construct(
        private ?string $cardNumber = null,
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?string $status = null
    ) {
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
