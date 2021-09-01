<?php

namespace App\Infrastructure\Offer\Entity;

use App\Infrastructure\Shared\Entity\DbEntity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

class DbPackage implements DbEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;
    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $gender;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $status;

    /**
     * @ORM\Column(type="float")
     */
    private float $price;

    /**
     * @ORM\OneToOne(targetEntity="DbPackageType", inversedBy="id", cascade="{persist}")
     */
    private DbPackageType $type;

    public function __construct(UuidInterface $id, string $name, string $gender, string $status, float $price, DbPackageType $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->gender = $gender;
        $this->status = $status;
        $this->price = $price;
        $this->type = $type;
    }
}
