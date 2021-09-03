<?php

namespace App\Tests\Domain\Offer\Model;

use App\Domain\Offer\Exception\InvalidOfferStatusException;
use App\Domain\Offer\Exception\OfferUpdateBlockedException;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class TicketOfferWithNumberOfDaysAndGenderTest extends TestCase
{
    public function testIsActiveOfferTicketWhenOtherStatus(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2'),
            new OfferName('test'),
            new Money(0),
            OfferStatus::NOT_ACTIVE(),
            new NumberOfDays(5),
            Gender::FEMALE(),
        );

        $this->assertFalse($offer->isActive());
    }

    public function testTryEditOfferNameWhenDisabledEditing(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2'),
            new OfferName('test'),
            new Money(0),
            OfferStatus::ACTIVE(),
            new NumberOfDays(5),
            Gender::FEMALE()
        );

        $offer->disableEditing();
        $this->expectException(OfferUpdateBlockedException::class);
        $offer->updateOfferName(new OfferName('new-name'));
    }

    public function testSuccessfulUpdateName(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2'),
            new OfferName('test'),
            new Money(0),
            OfferStatus::ACTIVE(),
            new NumberOfDays(5),
            Gender::FEMALE()
        );

        $offer->updateOfferName(new OfferName('new-name'));

        $this->assertEquals('new-name', (string) $offer->getName());
    }

    public function testSuccessfulUpdatePriceWhenEnableEditing(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2'),
            new OfferName('test'),
            new Money(0),
            OfferStatus::NOT_ACTIVE(),
            new NumberOfDays(5),
            Gender::FEMALE()
        );

        $offer->enableEditing();
        $offer->updatePrice(new Money(5));

        $this->assertEquals(5, $offer->getPrice()->getPrice());
    }

    public function testFailedEnableEditingWhenOfferIsActive(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2'),
            new OfferName('test'),
            new Money(0),
            OfferStatus::ACTIVE(),
            new NumberOfDays(5),
            Gender::FEMALE()
        );

        $this->expectException(InvalidOfferStatusException::class);
        $offer->enableEditing();
    }

    public function testFailedDisableEditingWhenOfferIsNotActive(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2'),
            new OfferName('test'),
            new Money(0),
            OfferStatus::NOT_ACTIVE(),
            new NumberOfDays(5),
            Gender::FEMALE()
        );

        $this->expectException(InvalidOfferStatusException::class);
        $offer->disableEditing();
    }

    public function testSuccessfulUpdateQuantity(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2'),
            new OfferName('test'),
            new Money(0),
            OfferStatus::ACTIVE(),
            new NumberOfDays(5),
            Gender::FEMALE()
        );

        $offer->updateQuantity(new NumberOfDays(10));

        $this->assertEquals(10, $offer->getQuantity()->getValue());
    }
}
