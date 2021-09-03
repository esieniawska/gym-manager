<?php

namespace App\Tests\Application\Offer\Service;

use App\Application\Offer\Assembler\OfferDtoAssembler;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Service\GetOfferService;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Offer\OfferFacade;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class GetOfferServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferFacade $offerFacadeMock;
    private ObjectProphecy|OfferDtoAssembler $offerDtoAssemblerMock;
    private GetOfferService $service;

    protected function setUp(): void
    {
        $this->offerFacadeMock = $this->prophesize(OfferFacade::class);
        $this->offerDtoAssemblerMock = $this->prophesize(OfferDtoAssembler::class);
        $this->service = new GetOfferService(
            $this->offerFacadeMock->reveal(),
            $this->offerDtoAssemblerMock->reveal()
        );
    }

    public function testGetOfferById(): void
    {
        $offer = new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(3),
            Gender::MALE()
        );
        $dto = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_ENTRIES,
            'offer-name',
            1.02,
            OfferStatus::ACTIVE,
            3,
            Gender::MALE
        );
        $this->offerFacadeMock->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($offer);
        $this->offerDtoAssemblerMock->assembleDomainObjectToDto($offer)->willReturn($dto);

        $result = $this->service->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertEquals($dto, $result);
    }
}
