<?php

namespace App\Tests\Infrastructure\Offer\Converter;

use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Offer\Converter\DbOfferConverter;
use App\Infrastructure\Offer\Entity\DbOffer;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Offer\Exception\InvalidOfferTypeException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DbOfferConverterTest extends TestCase
{
    use ProphecyTrait;

    public function testConvertDomainObjectWithNumberOfEntriesAndGenderToDbModel(): void
    {
        $offer = new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(3),
            Gender::MALE()
        );

        $converter = new DbOfferConverter();
        $result = $converter->convertDomainObjectToDbModel($offer);
        $this->assertInstanceOf(DbOffer::class, $result);
        $this->assertEquals(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(), $result->getType());
    }

    public function testConvertDomainObjectWithNumberOfDaysToDbModel(): void
    {
        $offer = new TicketOfferWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3)
        );

        $converter = new DbOfferConverter();
        $result = $converter->convertDomainObjectToDbModel($offer);
        $this->assertInstanceOf(DbOffer::class, $result);
        $this->assertEquals(OfferTypeEnum::TYPE_NUMBER_OF_DAYS(), $result->getType());
        $this->assertEmpty($result->getGender());
    }

    public function testConvertDomainObjectDbModelWhenInvalidType(): void
    {
        $offer = $this->prophesize(OfferTicket::class);
        $offer->getId()->willReturn(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));
        $offer->getName()->willReturn(new OfferName('offer-name'));
        $offer->getStatus()->willReturn(OfferStatus::ACTIVE());

        $converter = new DbOfferConverter();
        $this->expectException(InvalidOfferTypeException::class);
        $converter->convertDomainObjectToDbModel($offer->reveal());
    }
}
