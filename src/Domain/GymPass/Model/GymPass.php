<?php

declare(strict_types=1);

namespace App\Domain\GymPass\Model;

use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\Uuid;

abstract class GymPass extends DomainModel
{
    /**
     * @var GymEntering[]
     */
    protected array $gymEntering = [];

    public function __construct(
        private Uuid $id,
        private Client $client,
        private \DateTimeImmutable $startDate
    ) {
        parent::__construct($id);
    }

    abstract protected function isActive(\DateTimeImmutable $currentDate): bool;

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function canUsePass(): bool
    {
        $currentDate = new \DateTimeImmutable();
        $isGreaterOrEqualsDateThanStartDate = $currentDate->getTimestamp() >= $this->startDate->getTimestamp();

        return $isGreaterOrEqualsDateThanStartDate && $this->isActive($currentDate);
    }

    /**
     * @return GymEntering[]
     */
    public function getGymEntering(): array
    {
        return $this->gymEntering;
    }

    public function addGymEntering(GymEntering $gymEntering): void
    {
        if (!$this->canUsePass()) {
            throw new InactiveGymPassException('Inactive gym pass');
        }

        $this->gymEntering[] = $gymEntering;
    }
}
