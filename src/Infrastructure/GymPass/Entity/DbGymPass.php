<?php

declare(strict_types=1);

namespace App\Infrastructure\GymPass\Entity;

use App\Infrastructure\Shared\Entity\DbEntity;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Ramsey\Uuid\UuidInterface;

/**
 * @Entity()
 * @ORM\Table(name="gym_pass")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @DiscriminatorMap({"passWithEndDate" = "DbGymPassWithEndDate", "passWithNumberOfEntries" = "DbGymPassWithNumberOfEntries"})
 * @codeCoverageIgnore
 */
abstract class DbGymPass implements DbEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    protected UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $buyerCardNumber;

    /**
     * @ORM\Column(type="date_immutable")
     */
    protected DateTimeImmutable $startDate;

    public function __construct(UuidInterface $id, string $buyerCardNumber, DateTimeImmutable $startDate)
    {
        $this->id = $id;
        $this->buyerCardNumber = $buyerCardNumber;
        $this->startDate = $startDate;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getBuyerCardNumber(): string
    {
        return $this->buyerCardNumber;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }
}
