<?php

declare(strict_types=1);

namespace App\Application\Order\Dto;

class CreateOrderDto
{
    public function __construct(
        private string $cardNumber,
        private string $offerId,
        private \DateTimeImmutable $startDate
    ) {
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function getOfferId(): string
    {
        return $this->offerId;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }
}
