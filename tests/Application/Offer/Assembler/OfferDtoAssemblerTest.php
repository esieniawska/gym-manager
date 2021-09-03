<?php

namespace App\Tests\Application\Offer\Assembler;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Exception\InvalidOfferTypeException;
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
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OfferDtoAssemblerTest extends TestCase
{
    use ProphecyTrait;

    public function testAssembleDomainObjectWithNumberOfEntriesAndGenderToDto(): void
    {
        $offer = new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(3),
            Gender::MALE()
        );

        $assembler = new OfferDtoAssembler();
        $result = $assembler->assembleDomainObjectToDto($offer);
        $this->assertInstanceOf(OfferDto::class, $result);
        $this->assertEquals(OfferDto::TYPE_NUMBER_OF_ENTRIES, $result->getType());
    }

    public function testAssembleDomainObjectWithNumberOfDaysToDto(): void
    {
        $offer = new TicketOfferWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3)
        );

        $assembler = new OfferDtoAssembler();
        $result = $assembler->assembleDomainObjectToDto($offer);
        $this->assertInstanceOf(OfferDto::class, $result);
        $this->assertEquals(OfferDto::TYPE_NUMBER_OF_DAYS, $result->getType());
        $this->assertEmpty($result->getGender());
    }

    public function testTryAssembleDomainObjectDtoWhenInvalidType(): void
    {
        $offer = $this->prophesize(OfferTicket::class);
        $offer->getId()->willReturn(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));

        $assembler = new OfferDtoAssembler();
        $this->expectException(InvalidOfferTypeException::class);
        $assembler->assembleDomainObjectToDto($offer->reveal());
    }
}
