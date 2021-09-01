<?php

declare(strict_types=1);

namespace App\Domain\GymPass\Model;

use App\Domain\Shared\ValueObject\Uuid;

class GymPassWithNumberOfDays extends GymPass
{
    public function __construct(
       private Uuid $id,
       private Client $client,
       private \DateTimeImmutable $startDate,
       private \DateTimeImmutable $endDate
    ) {
        parent::__construct($id, $client, $startDate);
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }
}
