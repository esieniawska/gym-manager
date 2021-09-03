<?php

declare(strict_types=1);

namespace App\Application\Offer\Dto;

class UpdateOfferDto
{
    public function __construct(
        private string $id,
        private string $name,
        private float $price,
        private int $quantity,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
