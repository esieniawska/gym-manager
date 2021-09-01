<?php

declare(strict_types=1);

namespace App\Domain\GymPass\Model;

use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\Uuid;

abstract class GymPass extends DomainModel
{
    public function __construct(
        private Uuid $id,
        private Client $client,
        private \DateTimeImmutable $startDate
    ) {
        parent::__construct($id);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }
}
