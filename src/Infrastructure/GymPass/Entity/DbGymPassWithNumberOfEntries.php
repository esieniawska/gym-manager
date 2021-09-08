<?php

namespace App\Infrastructure\GymPass\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @codeCoverageIgnore
 */
class DbGymPassWithNumberOfEntries extends DbGymPass
{
    /**
     * @ORM\Column(type="integer")
     */
    private int $numberOfEntries;

    public function __construct(
        UuidInterface $id,
        string $buyerCardNumber,
        DateTimeImmutable $startDate,
        int $numberOfEntries
    ) {
        parent::__construct($id, $buyerCardNumber, $startDate);
        $this->numberOfEntries = $numberOfEntries;
    }

    public function getNumberOfEntries(): int
    {
        return $this->numberOfEntries;
    }
}
