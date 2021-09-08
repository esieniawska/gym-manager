<?php

namespace App\Tests\Infrastructure\Offer\Factory;

use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Infrastructure\Offer\Entity\DbOffer;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Offer\Factory\OfferFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class OfferFactoryTest extends TestCase
{
    public function testCreateOfferTicketWithNumberOfDays(): void
    {
        $dbModel = new DbOffer(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'offer-name',
            OfferStatus::ACTIVE(),
            OfferTypeEnum::TYPE_NUMBER_OF_DAYS(),
            102,
            3,
            null
        );

        $factory = new OfferFactory();
        $result = $factory->createOfferTicket($dbModel);
        $this->assertInstanceOf(TicketOfferWithNumberOfDays::class, $result);
    }

    public function testCreateOfferTicketWithNumberOfEntries(): void
    {
        $dbModel = new DbOffer(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'offer-name',
            OfferStatus::ACTIVE(),
            OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(),
            102,
            3,
            null
        );

        $factory = new OfferFactory();
        $result = $factory->createOfferTicket($dbModel);
        $this->assertInstanceOf(TicketOfferWithNumberOfEntries::class, $result);
    }
}
