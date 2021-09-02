<?php

namespace App\Tests\Application\Offer\Factory;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Factory\CreateOfferFactory;
use App\Application\Offer\Factory\CreateOfferWithGenderFactory;
use App\Application\Offer\Factory\OfferFactory;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class OfferFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|CreateOfferWithGenderFactory $createOfferWithGenderFactoryMock;
    private ObjectProphecy|CreateOfferFactory $createOfferFactoryMock;
    private OfferFactory $factory;

    protected function setUp(): void
    {
        $this->createOfferWithGenderFactoryMock = $this->prophesize(CreateOfferWithGenderFactory::class);
        $this->createOfferFactoryMock = $this->prophesize(CreateOfferFactory::class);
        $this->factory = new OfferFactory(
            $this->createOfferWithGenderFactoryMock->reveal(),
            $this->createOfferFactoryMock->reveal()
        );
    }

    public function testCreateOfferTicketWithoutGender(): void
    {
        $dto = new CreateNumberOfDaysOfferDto('new-offer', 1, 1);
        $this->createOfferWithGenderFactoryMock->createOfferTicket($dto)->shouldNotBeCalled();

        $offer = new TicketOfferWithNumberOfDays(
            new Uuid('ce871a0b-567d-475d-ac7e-33210e314152'),
            new OfferName('new-offer'),
            new Money(1),
            OfferStatus::ACTIVE(),
            new NumberOfDays(1)
        );
        $this->createOfferFactoryMock->createOfferTicket($dto)->willReturn($offer);
        $result = $this->factory->createOfferTicket($dto);
        $this->assertEquals($offer, $result);
    }

    public function testCreateOfferTicketWithGender(): void
    {
        $dto = new CreateNumberOfDaysOfferDto('new-offer', 1, 1, Gender::MALE);

        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('ce871a0b-567d-475d-ac7e-33210e314152'),
            new OfferName('new-offer'),
            new Money(1),
            OfferStatus::ACTIVE(),
            new NumberOfDays(1),
            Gender::MALE()
        );
        $this->createOfferWithGenderFactoryMock->createOfferTicket($dto)->willReturn($offer);
        $this->createOfferFactoryMock->createOfferTicket($dto)->shouldNotBeCalled();

        $result = $this->factory->createOfferTicket($dto);
        $this->assertEquals($offer, $result);
    }
}
