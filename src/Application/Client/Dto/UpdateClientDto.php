<?php

declare(strict_types=1);

namespace App\Application\Client\Dto;

class UpdateClientDto extends CreateClientDto
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private string $gender,
        private string $status,
        private ?string $phoneNumber,
        private ?string $email
    ) {
        parent::__construct($firstName, $lastName, $gender, $phoneNumber, $email);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
