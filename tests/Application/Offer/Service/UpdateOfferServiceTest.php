<?php

namespace App\Tests\Application\Offer\Service;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Dto\UpdateOfferDto;
use App\Application\Offer\Exception\InvalidOfferTypeException;
use App\Application\Offer\Service\UpdateOfferService;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Domain\Offer\OfferFacade;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class UpdateOfferServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferFacade $offerFacadeMock;
    private ObjectProphecy|OfferDtoAssembler $offerDtoAssemblerMock;
    private ObjectProphecy|OfferRepository $offerRepositoryMock;
    private UpdateOfferService $updateOfferService;

    protected function setUp(): void
    {
        $this->offerFacadeMock = $this->prophesize(OfferFacade::class);
        $this->offerDtoAssemblerMock = $this->prophesize(OfferDtoAssembler::class);
        $this->offerRepositoryMock = $this->prophesize(OfferRepository::class);
        $this->updateOfferService = new UpdateOfferService(
            $this->offerFacadeMock->reveal(),
            $this->offerDtoAssemblerMock->reveal(),
            $this->offerRepositoryMock->reveal()
        );
    }

    public function testUpdateOfferWithNumberOfDays(): void
    {
        $updateDto = new UpdateOfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            'offer',
            2,
            10
        );

        $offer = $this->prophesize(TicketOfferWithNumberOfDays::class);
        $offer->updateQuantity(Argument::type(NumberOfDays::class))->shouldBeCalled();
        $offer->updatePrice(Argument::type(Money::class))->shouldBeCalled();
        $offer->updateOfferName(Argument::type(OfferName::class))->shouldBeCalled();

        $this->offerFacadeMock->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($offer->reveal());
        $this->offerRepositoryMock
            ->updateOffer(Argument::type(TicketOfferWithNumberOfDays::class))
            ->shouldBeCalled();

        $dto = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_DAYS,
            'offer',
            2,
            OfferStatus::ACTIVE,
            10
        );
        $this->offerDtoAssemblerMock
            ->assembleDomainObjectToDto(Argument::type(TicketOfferWithNumberOfDays::class))
            ->willReturn($dto);

        $result = $this->updateOfferService->updateOffer($updateDto);
        $this->assertInstanceOf(OfferDto::class, $result);
    }

    public function testUpdateOfferWithNumberOfEntries(): void
    {
        $updateDto = new UpdateOfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            'offer',
            2,
            10
        );

        $offer = $this->prophesize(TicketOfferWithNumberOfEntries::class);
        $offer->updateQuantity(Argument::type(NumberOfEntries::class))->shouldBeCalled();
        $offer->updatePrice(Argument::type(Money::class))->shouldBeCalled();
        $offer->updateOfferName(Argument::type(OfferName::class))->shouldBeCalled();

        $this->offerFacadeMock->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($offer->reveal());
        $this->offerRepositoryMock
            ->updateOffer(Argument::type(TicketOfferWithNumberOfEntries::class))
            ->shouldBeCalled();

        $dto = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_ENTRIES,
            'offer',
            2,
            OfferStatus::ACTIVE,
            10
        );
        $this->offerDtoAssemblerMock
            ->assembleDomainObjectToDto(Argument::type(TicketOfferWithNumberOfEntries::class))
            ->willReturn($dto);

        $result = $this->updateOfferService->updateOffer($updateDto);
        $this->assertInstanceOf(OfferDto::class, $result);
    }

    public function testTryUpdateOfferWhenInvalidOfferType(): void
    {
        $updateDto = new UpdateOfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            'offer',
            2,
            10
        );

        $offer = $this->prophesize(OfferTicket::class);
        $this->offerFacadeMock->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($offer->reveal());

        $this->expectException(InvalidOfferTypeException::class);
        $this->updateOfferService->updateOffer($updateDto);
    }
}
