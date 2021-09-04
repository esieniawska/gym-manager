<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

use App\Domain\Offer\Exception\InvalidOfferStatusException;
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

    public function canBeOrdered(): bool
    {
        return $this->status->isTheSameType(OfferStatus::ACTIVE());
    }

    public function disableEditing(): void
    {
        if ($this->editingDisabled()) {
            throw new InvalidOfferStatusException('Offer is already blocked.');
        }

        $this->status = OfferStatus::NOT_ACTIVE();
    }

    public function enableEditing(): void
    {
        if (!$this->editingDisabled()) {
            throw new InvalidOfferStatusException('Offer is already active.');
        }

        $this->status = OfferStatus::ACTIVE();
    }

    /**
     * @throws OfferUpdateBlockedException
     */
    public function updateOfferName(OfferName $name): void
    {
        $this->ensureIsEnabledEditing();
        $this->name = $name;
    }

    /**
     * @throws OfferUpdateBlockedException
     */
    public function updatePrice(Money $price): void
    {
        $this->ensureIsEnabledEditing();
        $this->price = $price;
    }

    /**
     * @throws OfferUpdateBlockedException
     */
    public function updateQuantity(PositiveValue $value): void
    {
        $this->ensureIsEnabledEditing();
        $this->quantity = $value;
    }

    protected function ensureIsEnabledEditing(): void
    {
        if ($this->editingDisabled()) {
            throw new OfferUpdateBlockedException('Offer update blocked.');
        }
    }

    public function editingDisabled(): bool
    {
        return $this->status->isTheSameType(OfferStatus::NOT_ACTIVE());
    }
}
