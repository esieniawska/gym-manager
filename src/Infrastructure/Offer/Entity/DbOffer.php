<?php

declare(strict_types=1);

namespace App\Infrastructure\Offer\Entity;

use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\ValueObject\Gender;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Shared\Entity\DbEntity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Offer\Repository\DoctrineOfferRepository")
 * @ORM\Table(name="offer")
 * @codeCoverageIgnore
 */
class DbOffer implements DbEntity
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
     * @ORM\Column(type="offer_status")
     */
    private OfferStatus $status;

    /**
     * @ORM\Column(type="offer_type")
     */
    private OfferTypeEnum $type;

    /**
     * @ORM\Column(type="integer")
     */
    private int $price;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    /**
     * @ORM\Column(type="gender_type",  nullable=true)
     */
    private ?Gender $gender;

    public function __construct(
        UuidInterface $id,
        string $name,
        OfferStatus $status,
        OfferTypeEnum $type,
        int $price,
        int $quantity,
        ?Gender $gender
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->type = $type;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->gender = $gender;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): OfferStatus
    {
        return $this->status;
    }

    public function getType(): OfferTypeEnum
    {
        return $this->type;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setStatus(OfferStatus $status): void
    {
        $this->status = $status;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
