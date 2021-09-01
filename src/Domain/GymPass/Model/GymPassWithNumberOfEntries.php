<?php

declare(strict_types=1);

namespace App\Domain\GymPass\Model;

use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class GymPassWithNumberOfEntries extends GymPass
{
    public function __construct(
        protected Uuid $id,
        protected Client $client,
        protected \DateTimeImmutable $startDate,
        private NumberOfEntries $numberOfEntries,
        protected array $gymEntering = []
    ) {
        parent::__construct($id, $client, $startDate, $gymEntering);
    }

    public function getNumberOfEntries(): NumberOfEntries
    {
        return $this->numberOfEntries;
    }

    protected function isActive(\DateTimeImmutable $currentDate): bool
    {
        return $this->getNumberOfEntries()->getValue() > count($this->gymEntering);
    }
}
