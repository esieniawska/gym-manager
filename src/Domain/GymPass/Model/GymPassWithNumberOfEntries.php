<?php

declare(strict_types=1);

namespace App\Domain\GymPass\Model;

use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;

class GymPassWithNumberOfEntries extends GymPass
{
    public function __construct(
        private Uuid $id,
        private Client $client,
        private \DateTimeImmutable $startDate,
        private NumberOfEntries $numberOfEntries
    ) {
        parent::__construct($id, $client, $startDate);
    }

    public function getNumberOfEntries(): NumberOfEntries
    {
        return $this->numberOfEntries;
    }
}
