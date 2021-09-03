<?php

namespace App\Tests\Infrastructure\Offer\Factory;

use App\Application\Offer\Exception\InvalidOfferTypeException;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Infrastructure\Offer\Entity\DbOffer;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Offer\Factory\OfferWithGenderFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Uuid\Uuid as RamseyUuid;

class OfferWithGenderFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testCreateOfferTicketWithNumberOfDays(): void
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

        $factory = new OfferWithGenderFactory();
        $result = $factory->createOfferTicket($dbModel);
        $this->assertInstanceOf(TicketOfferWithNumberOfDaysAndGender::class, $result);
    }

    public function testCreateOfferTicketWithNumberOfEntries(): void
    {
        $dbModel = new DbOffer(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'offer-name',
            OfferStatus::ACTIVE(),
            OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(),
            1.02,
            3,
            Gender::MALE()
        );

        $factory = new OfferWithGenderFactory();
        $result = $factory->createOfferTicket($dbModel);
        $this->assertInstanceOf(TicketOfferWithNumberOfEntriesAndGender::class, $result);
    }

    public function testTryCreateOfferTicketWhenInvalidType(): void
    {
        $dbModel = $this->prophesize(DbOffer::class);
        $type = $this->prophesize(OfferTypeEnum::class);
        $dbModel->getType()->willReturn($type->reveal());

        $factory = new OfferWithGenderFactory();
        $this->expectException(InvalidOfferTypeException::class);
        $factory->createOfferTicket($dbModel->reveal());
    }
}
