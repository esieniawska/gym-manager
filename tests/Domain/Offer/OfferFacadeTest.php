<?php

namespace App\Tests\Domain\Offer;

use App\Domain\Offer\Exception\OfferNotFoundException;
use App\Domain\Offer\Model\NumberOfDays;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\OfferFacade;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class OfferFacadeTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferRepository $offerRepositoryMock;
    private OfferFacade $facade;

    protected function setUp(): void
    {
        $this->offerRepositoryMock = $this->prophesize(OfferRepository::class);
        $this->facade = new OfferFacade($this->offerRepositoryMock->reveal());
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
}
