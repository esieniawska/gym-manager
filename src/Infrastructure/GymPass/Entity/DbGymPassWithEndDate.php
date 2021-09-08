<?php

namespace App\Infrastructure\GymPass\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @codeCoverageIgnore
 */
class DbGymPassWithEndDate extends DbGymPass
{
    /**
     * @ORM\Column(type="date_immutable")
     */
    private DateTimeImmutable $endDate;

    /**
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private ?DateTimeImmutable $lockStartDate;

    /**
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private ?DateTimeImmutable $lockEndDate;

    public function __construct(
        UuidInterface $id,
        string $buyerCardNumber,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        ?DateTimeImmutable $lockStartDate = null,
        ?DateTimeImmutable $lockEndDate = null,
    ) {
        parent::__construct($id, $buyerCardNumber, $startDate);
        $this->endDate = $endDate;
        $this->lockStartDate = $lockStartDate;
        $this->lockEndDate = $lockEndDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getLockStartDate(): ?DateTimeImmutable
    {
        return $this->lockStartDate;
    }

    public function getLockEndDate(): ?DateTimeImmutable
    {
        return $this->lockEndDate;
    }
}
