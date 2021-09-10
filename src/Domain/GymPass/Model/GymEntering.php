<?php

declare(strict_types=1);

namespace App\Domain\GymPass\Model;

class GymEntering
{
    public function __construct(
        private \DateTimeImmutable $date
    ) {
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
