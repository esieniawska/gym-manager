<?php

namespace App\Application\GymPass\Dto;

class GymPassLockingDto
{
    public function __construct(private string $gymPassId, private int $numberOfDays)
    {
    }

    public function getGymPassId(): string
    {
        return $this->gymPassId;
    }

    public function getNumberOfDays(): int
    {
        return $this->numberOfDays;
    }
}
