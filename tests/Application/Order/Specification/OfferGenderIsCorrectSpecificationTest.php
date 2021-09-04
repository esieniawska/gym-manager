<?php

namespace App\Tests\Application\Order\Specification;

use App\Application\Order\Specification\OfferGenderIsCorrectSpecification;
use App\Domain\Client\Model\Client;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OfferGenderIsCorrectSpecificationTest extends TestCase
{
    use ProphecyTrait;

    public function testIsSatisfiedByWhenClientHasOtherGender(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer'),
            new Money(3.33),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $client = $this->prophesize(Client::class);
        $client->getGender()->willReturn(Gender::FEMALE());
        $specification = new OfferGenderIsCorrectSpecification($client->reveal());
        $this->assertFalse($specification->isSatisfiedBy($offer));
    }

    public function testIsSatisfiedByWhenClientHasAcceptedGender(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer'),
            new Money(3.33),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $client = $this->prophesize(Client::class);
        $client->getGender()->willReturn(Gender::MALE());
        $specification = new OfferGenderIsCorrectSpecification($client->reveal());
        $this->assertTrue($specification->isSatisfiedBy($offer));
    }
}
