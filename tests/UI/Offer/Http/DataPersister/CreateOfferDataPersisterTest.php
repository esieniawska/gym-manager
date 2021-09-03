<?php

namespace App\Tests\UI\Offer\Http\DataPersister;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\OfferDto as ApplicationDto;
use App\Application\Offer\Service\CreateOfferService;
use App\Domain\Offer\Model\OfferStatus;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\DataPersister\CreateOfferDataPersister;
use App\UI\Offer\Http\Dto\OfferDto;
use App\UI\Offer\Http\Dto\OfferType;
use App\UI\Offer\Http\Factory\CreateOfferDtoFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateOfferDataPersisterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|CreateOfferService $createOfferServiceMock;
    private ObjectProphecy|CreateOfferDtoFactory $createOfferDtoFactoryMock;
    private ObjectProphecy|OfferDtoConverter $offerDtoConverterMock;
    private CreateOfferDataPersister $persister;

    protected function setUp(): void
    {
        $this->createOfferServiceMock = $this->prophesize(CreateOfferService::class);
        $this->createOfferDtoFactoryMock = $this->prophesize(CreateOfferDtoFactory::class);
        $this->offerDtoConverterMock = $this->prophesize(OfferDtoConverter::class);
        $this->persister = new CreateOfferDataPersister(
            $this->createOfferServiceMock->reveal(),
            $this->createOfferDtoFactoryMock->reveal(),
            $this->offerDtoConverterMock->reveal()
        );
    }

    public function testPersist(): void
    {
        $data = (new OfferDto())
            ->setName('offer-name')
            ->setPrice(1.3)
            ->setQuantity(11)
            ->setType(OfferType::TYPE_NUMBER_OF_DAYS);
        $dto = new CreateNumberOfDaysOfferDto('offer-name', 1.3, 11);

        $this->createOfferDtoFactoryMock->createDtoFromHttp($data)->willReturn($dto)->shouldBeCalled();

        $applicationDto = new ApplicationDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            ApplicationDto::TYPE_NUMBER_OF_DAYS,
            'offer-name',
            1.3,
            OfferStatus::ACTIVE,
            11,
        null
        );
        $this->createOfferServiceMock->create($dto)->willReturn($applicationDto);

        $httpDto = (new OfferDto())
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setType(OfferTypeEnum::TYPE_NUMBER_OF_DAYS)
            ->setQuantity(1)
            ->setPrice(1.3)
            ->setName('offer-name')
            ->setStatus(OfferStatus::ACTIVE);
        $this->offerDtoConverterMock->createHttpFromApplicationDto($applicationDto)
            ->willReturn($httpDto);

        $result = $this->persister->persist($data);
        $this->assertInstanceOf(OfferDto::class, $result);
    }
}
