<?php

namespace App\Tests\Application\Offer;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Exception\OfferCanNotBeOrderedException;
use App\Application\Offer\Exception\OfferNotFoundException;
use App\Application\Offer\OfferFacade;
use App\Domain\Offer\Model\Filter;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class OfferFacadeTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferRepository $offerRepositoryMock;
    private ObjectProphecy|OfferDtoAssembler $offerDtoAssemblerMock;
    private OfferFacade $facade;

    protected function setUp(): void
    {
        $this->offerRepositoryMock = $this->prophesize(OfferRepository::class);
        $this->offerDtoAssemblerMock = $this->prophesize(OfferDtoAssembler::class);
        $this->facade = new OfferFacade($this->offerRepositoryMock->reveal(), $this->offerDtoAssemblerMock->reveal());
    }

    public function testGetOfferByIdWhenOfferNotFound(): void
    {
        $this->offerRepositoryMock->getOfferById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'))->willReturn(null);
        $this->expectException(OfferNotFoundException::class);
        $this->facade->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
    }

    public function testGetOfferByIdWhenOfferExist(): void
    {
        $offerTicket = new TicketOfferWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('name'),
            new Money(5),
            OfferStatus::ACTIVE(),
            new NumberOfDays(4)
        );
        $this->offerRepositoryMock
            ->getOfferById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'))
            ->willReturn($offerTicket);

        $result = $this->facade->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertEquals($offerTicket, $result);
    }

    public function testGetOfferByIdThatCannotBeOrdered(): void
    {
        $offerTicket = new TicketOfferWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('name'),
            new Money(5),
            OfferStatus::NOT_ACTIVE(),
            new NumberOfDays(4)
        );
        $this->offerRepositoryMock
            ->getOfferById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'))
            ->willReturn($offerTicket);

        $this->expectException(OfferCanNotBeOrderedException::class);
        $this->facade->getOfferByIdThatCanBeOrdered('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
    }

    public function testGetOfferByIdThatCanBeOrdered(): void
    {
        $offerTicket = new TicketOfferWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('name'),
            new Money(5),
            OfferStatus::ACTIVE(),
            new NumberOfDays(4)
        );
        $this->offerRepositoryMock
            ->getOfferById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'))
            ->willReturn($offerTicket);

        $dto = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_DAYS,
            'name',
            0.05,
            OfferStatus::ACTIVE,
            4
        );
        $this->offerDtoAssemblerMock->assembleDomainObjectToDto($offerTicket)->willReturn($dto);
        $result = $this->facade->getOfferByIdThatCanBeOrdered('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertEquals($dto, $result);
    }

    public function testGetAllOffers(): void
    {
        $offerTicket = new TicketOfferWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('name'),
            new Money(5),
            OfferStatus::ACTIVE(),
            new NumberOfDays(4)
        );
        $this->offerRepositoryMock
            ->getAll(new Filter())
            ->willReturn(new ArrayCollection([$offerTicket]));

        $result = $this->facade->getAllOffers(new Filter());
        $this->assertInstanceOf(TicketOfferWithNumberOfDays::class, $result->first());
    }
}
