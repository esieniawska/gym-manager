<?php

declare(strict_types=1);

namespace App\Application\GymPass\Dto;

class GymPassLockingResult
{
    public function __construct(
        private \DateTimeImmutable $endDate,
        private \DateTimeImmutable $lockStartDate,
        private \DateTimeImmutable $lockEndDate,
    ) {
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getLockStartDate(): \DateTimeImmutable
    {
        return $this->lockStartDate;
    }

    public function getLockEndDate(): \DateTimeImmutable
    {
        return $this->lockEndDate;
    }
}
