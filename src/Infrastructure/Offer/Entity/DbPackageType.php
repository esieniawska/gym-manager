<?php

namespace App\Infrastructure\Offer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="package_type")
 */
class DbPackageType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $value;

    /**
     * @ORM\OneToOne(targetEntity="DbPackage", mappedBy="id")
     */
    private DbPackage $package;

    public function __construct(UuidInterface $id, string $name, int $value)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }
}
