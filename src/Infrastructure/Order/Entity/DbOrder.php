<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Entity;

use App\Infrastructure\Order\Enum\OrderTypeEnum;
use App\Infrastructure\Shared\Entity\DbEntity;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Order\Repository\DoctrineOrderRepository")
 * @ORM\Table(name="`order`")
 * @codeCoverageIgnore
 */
class DbOrder implements DbEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $orderItemId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $buyerCardNumber;

    /**
     * @ORM\Column(type="order_type")
     */
    private OrderTypeEnum $type;

    /**
     * @ORM\Column(type="integer")
     */
    private int $price;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private DateTimeImmutable $startDate;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(
        UuidInterface $id,
        UuidInterface $orderItemId,
        string $buyerCardNumber,
        OrderTypeEnum $type,
        int $price,
        int $quantity,
        DateTimeImmutable $startDate,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->orderItemId = $orderItemId;
        $this->buyerCardNumber = $buyerCardNumber;
        $this->type = $type;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->startDate = $startDate;
        $this->createdAt = $createdAt;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getType(): OrderTypeEnum
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

    public function getOrderItemId(): UuidInterface
    {
        return $this->orderItemId;
    }

    public function getBuyerCardNumber(): string
    {
        return $this->buyerCardNumber;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
