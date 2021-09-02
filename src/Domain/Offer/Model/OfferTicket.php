<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Offer\Exception\OfferUpdateBlockedException;
use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\PositiveValue;
use App\Domain\Shared\ValueObject\Uuid;

abstract class OfferTicket extends DomainModel
{
    public function __construct(
        protected Uuid $id,
        protected OfferName $name,
        protected Money $price,
        protected OfferStatus $status,
        protected PositiveValue $quantity
    ) {
        parent::__construct($id);
    }

    public function getQuantity(): PositiveValue
    {
        return $this->quantity;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): OfferName
    {
        return $this->name;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getStatus(): OfferStatus
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status->isTheSameType(OfferStatus::ACTIVE());
    }

    public function disableEditing(): void
    {
        $this->status = OfferStatus::NOT_ACTIVE();
    }

    public function enableEditing(): void
    {
        $this->status = OfferStatus::ACTIVE();
    }

    public function updateOfferName(OfferName $name): void
    {
        $this->ensureIsEnabledEditing();
        $this->name = $name;
    }

    public function updatePrice(Money $price): void
    {
        $this->ensureIsEnabledEditing();
        $this->price = $price;
    }

    protected function ensureIsEnabledEditing(): void
    {
        if ($this->editingDisabled()) {
            throw new OfferUpdateBlockedException('Offer update blocked');
        }
    }

    public function editingDisabled(): bool
    {
        return $this->status->isTheSameType(OfferStatus::NOT_ACTIVE());
    }
}
