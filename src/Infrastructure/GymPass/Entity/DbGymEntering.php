<?php

namespace App\Infrastructure\GymPass\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Ramsey\Uuid\UuidInterface;

/**
 * @Entity()
 * @ORM\Table(name="gym_entering")
 * @codeCoverageIgnore
 */
class DbGymEntering
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\ManyToOne(targetEntity="DbGymPass", cascade={"persist"} )
     * @ORM\JoinColumn(referencedColumnName="id", name="gym_pass_id", nullable=false, onDelete="CASCADE")
     */
    private DbGymPass $gymPass;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $date;

    public function __construct(UuidInterface $id, DbGymPass $gymPass, DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->gymPass = $gymPass;
        $this->date = $date;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
