<?php

declare(strict_types=1);

namespace App\Application\Offer\Dto;

abstract class CreateOfferDto
{
    public function __construct(
        protected string $name,
        protected float $price,
        protected int $quantity,
        protected ?string $gender = null
    ) {
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

    public function getGender(): ?string
    {
        return $this->gender;
    }
}
