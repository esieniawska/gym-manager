<?php

declare(strict_types=1);

namespace App\Application\GymPass\Dto;

class AddGymEnteringDto
{
    public function __construct(private string $cardNumber, private string $gymPassId)
    {
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function getGymPassId(): string
    {
        return $this->gymPassId;
    }
}
