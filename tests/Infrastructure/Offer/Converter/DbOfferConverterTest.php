<?php

namespace App\Tests\Infrastructure\Offer\Converter;

use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
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
use App\Infrastructure\Offer\Factory\OfferFactory;
use App\Infrastructure\Offer\Factory\OfferWithGenderFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid as RamseyUuid;

class DbOfferConverterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferWithGenderFactory $offerWithGenderFactoryMock;
    private ObjectProphecy|OfferFactory $offerFactoryMock;
    private DbOfferConverter $converter;

    protected function setUp(): void
    {
        $this->offerWithGenderFactoryMock = $this->prophesize(OfferWithGenderFactory::class);
        $this->offerFactoryMock = $this->prophesize(OfferFactory::class);
        $this->converter = new DbOfferConverter(
            $this->offerWithGenderFactoryMock->reveal(),
            $this->offerFactoryMock->reveal()
        );
    }

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

        $result = $this->converter->convertDomainObjectToDbModel($offer);
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

        $result = $this->converter->convertDomainObjectToDbModel($offer);
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

        $this->expectException(InvalidOfferTypeException::class);
        $this->converter->convertDomainObjectToDbModel($offer->reveal());
    }

    public function testConvertDbModelToDomainObjectWithNumberOfDays(): void
    {
        $dbModel = new DbOffer(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'offer-name',
            OfferStatus::ACTIVE(),
            OfferTypeEnum::TYPE_NUMBER_OF_DAYS(),
            1.02,
            3,
            null
        );
        $domainModel = new TicketOfferWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3)
        );

        $this->offerFactoryMock->createOfferTicket($dbModel)->willReturn($domainModel);
        $this->offerWithGenderFactoryMock->createOfferTicket($dbModel)->shouldNotBeCalled();

        $result = $this->converter->convertDbModelToDomainObject($dbModel);
        $this->assertEquals($domainModel, $result);
    }

    public function testConvertDbModelToDomainObjectWithNumberOfDaysAndGender(): void
    {
        $dbModel = new DbOffer(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'offer-name',
            OfferStatus::ACTIVE(),
            OfferTypeEnum::TYPE_NUMBER_OF_DAYS(),
            1.02,
            3,
            Gender::MALE()
        );
        $domainModel = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $this->offerWithGenderFactoryMock->createOfferTicket($dbModel)->willReturn($domainModel);
        $this->offerFactoryMock->createOfferTicket($dbModel)->shouldNotBeCalled();

        $result = $this->converter->convertDbModelToDomainObject($dbModel);
        $this->assertEquals($domainModel, $result);
    }
}
