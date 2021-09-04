<?php

namespace App\Application\Offer\Dto;

use App\Application\Shared\Dto\BaseDto;

class OfferDto implements BaseDto
{
    public const TYPE_NUMBER_OF_ENTRIES = 'TYPE_NUMBER_OF_ENTRIES';
    public const TYPE_NUMBER_OF_DAYS = 'TYPE_NUMBER_OF_DAYS';

    public function __construct(
        private string $id,
        private string $type,
        private string $name,
        private float $price,
        private string $status,
        private int $quantity,
        private ?string $gender = null,
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
