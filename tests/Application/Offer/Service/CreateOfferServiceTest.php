<?php

namespace App\Tests\Application\Offer\Service;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Factory\OfferFactory;
use App\Application\Offer\Service\CreateOfferService;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateOfferServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferRepository $offerRepository;
    private ObjectProphecy|OfferFactory $offerFactory;
    private ObjectProphecy|OfferDtoAssembler $assemblerMock;
    private CreateOfferService $service;

    protected function setUp(): void
    {
        $this->offerRepository = $this->prophesize(OfferRepository::class);
        $this->offerFactory = $this->prophesize(OfferFactory::class);
        $this->assemblerMock = $this->prophesize(OfferDtoAssembler::class);
        $this->service = new CreateOfferService(
            $this->offerRepository->reveal(),
            $this->offerFactory->reveal(),
            $this->assemblerMock->reveal()
        );
    }

    public function testCreate(): void
    {
        $dto = new CreateNumberOfDaysOfferDto('new-offer', 1, 1);
        $offer = new TicketOfferWithNumberOfDays(
            new Uuid('ce871a0b-567d-475d-ac7e-33210e314152'),
            new OfferName('new-offer'),
            new Money(1),
            OfferStatus::ACTIVE(),
            new NumberOfDays(1)
        );

        $this->offerFactory->createOfferTicket($dto)->willReturn($offer)->shouldBeCalled();
        $this->offerRepository->addOffer($offer)->shouldBeCalled();

        $offerDto = new OfferDto(
            'ce871a0b-567d-475d-ac7e-33210e314152',
            OfferDto::TYPE_NUMBER_OF_DAYS,
            'new-offer',
            1,
            OfferStatus::ACTIVE,
            1,
            null
        );
        $this->assemblerMock->assembleDomainObjectToDto($offer)->willReturn($offerDto);
        $result = $this->service->create($dto);
        $this->assertInstanceOf(OfferDto::class, $result);
    }
}
