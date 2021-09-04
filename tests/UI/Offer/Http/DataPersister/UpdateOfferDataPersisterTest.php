<?php

namespace App\Tests\UI\Offer\Http\DataPersister;

use App\Application\Offer\Dto\OfferDto as ApplicationDto;
use App\Application\Offer\Dto\UpdateOfferDto;
use App\Application\Offer\Service\UpdateOfferService;
use App\Domain\Offer\Exception\InvalidOfferStatusException;
use App\Domain\Offer\Exception\OfferUpdateBlockedException;
use App\Domain\Offer\Model\OfferStatus;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\DataPersister\UpdateOfferDataPersister;
use App\UI\Offer\Http\Dto\OfferDto;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UpdateOfferDataPersisterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|UpdateOfferService $updateOfferService;
    private ObjectProphecy|OfferDtoConverter $offerDtoConverterMock;
    private UpdateOfferDataPersister $persister;

    protected function setUp(): void
    {
        $this->updateOfferService = $this->prophesize(UpdateOfferService::class);
        $this->offerDtoConverterMock = $this->prophesize(OfferDtoConverter::class);
        $this->persister = new UpdateOfferDataPersister(
            $this->updateOfferService->reveal(),
            $this->offerDtoConverterMock->reveal()
        );
    }

    public function testPersistUpdateOffer(): void
    {
        $data = (new OfferDto())
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setName('offer-name')
            ->setPrice(1.3)
            ->setQuantity(11);
        $dto = new UpdateOfferDto('7d24cece-b0c6-4657-95d5-31180ebfc8e1', 'offer-name', 1.3, 11);

        $applicationDto = new ApplicationDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            ApplicationDto::TYPE_NUMBER_OF_DAYS,
            'offer-name',
            1.3,
            OfferStatus::ACTIVE,
            11,
            null
        );
        $this->updateOfferService->updateOffer($dto)->willReturn($applicationDto)->shouldBeCalled();

        $httpDto = (new OfferDto())
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setType(OfferTypeEnum::TYPE_NUMBER_OF_DAYS)
            ->setQuantity(1)
            ->setPrice(1.3)
            ->setName('offer-name')
            ->setStatus(OfferStatus::ACTIVE);

        $this->offerDtoConverterMock->createHttpFromApplicationDto($applicationDto)
            ->willReturn($httpDto);

        $result = $this->persister->persist($data, ['item_operation_name' => OfferDto::OPERATION_UPDATE]);
        $this->assertInstanceOf(OfferDto::class, $result);
    }

    public function testPersistWhenUpdateBlocked(): void
    {
        $data = (new OfferDto())
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setName('offer-name')
            ->setPrice(1.3)
            ->setQuantity(11);
        $dto = new UpdateOfferDto('7d24cece-b0c6-4657-95d5-31180ebfc8e1', 'offer-name', 1.3, 11);

        $this->updateOfferService->updateOffer($dto)
            ->willThrow(OfferUpdateBlockedException::class);

        $this->expectException(AccessDeniedHttpException::class);
        $this->persister->persist($data, ['item_operation_name' => OfferDto::OPERATION_UPDATE]);
    }

    public function testPersistDisableOffer(): void
    {
        $data = (new OfferDto())->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');

        $applicationDto = new ApplicationDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            ApplicationDto::TYPE_NUMBER_OF_DAYS,
            'offer-name',
            1.3,
            OfferStatus::NOT_ACTIVE,
            11,
            null
        );
        $this->updateOfferService
            ->disableEditing('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn($applicationDto)
            ->shouldBeCalled();

        $httpDto = (new OfferDto())
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setType(OfferTypeEnum::TYPE_NUMBER_OF_DAYS)
            ->setQuantity(1)
            ->setPrice(1.3)
            ->setName('offer-name')
            ->setStatus(OfferStatus::NOT_ACTIVE);

        $this->offerDtoConverterMock->createHttpFromApplicationDto($applicationDto)
            ->willReturn($httpDto);

        $result = $this->persister->persist($data, ['item_operation_name' => OfferDto::OPERATION_DISABLE]);
        $this->assertInstanceOf(OfferDto::class, $result);
    }

    public function testPersistDisableOfferWhenOfferIsNotActive(): void
    {
        $data = (new OfferDto())->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');

        $this->updateOfferService
            ->disableEditing('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willThrow(InvalidOfferStatusException::class)
            ->shouldBeCalled();

        $this->expectException(AccessDeniedHttpException::class);
        $this->persister->persist($data, ['item_operation_name' => OfferDto::OPERATION_DISABLE]);
    }

    public function testPersistEnableOffer(): void
    {
        $data = (new OfferDto())->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');

        $applicationDto = new ApplicationDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            ApplicationDto::TYPE_NUMBER_OF_DAYS,
            'offer-name',
            1.3,
            OfferStatus::ACTIVE,
            11,
            null
        );
        $this->updateOfferService
            ->enableEditing('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn($applicationDto)
            ->shouldBeCalled();

        $httpDto = (new OfferDto())
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setType(OfferTypeEnum::TYPE_NUMBER_OF_DAYS)
            ->setQuantity(1)
            ->setPrice(1.3)
            ->setName('offer-name')
            ->setStatus(OfferStatus::ACTIVE);

        $this->offerDtoConverterMock->createHttpFromApplicationDto($applicationDto)
            ->willReturn($httpDto);

        $result = $this->persister->persist($data, ['item_operation_name' => OfferDto::OPERATION_ENABLE]);
        $this->assertInstanceOf(OfferDto::class, $result);
    }

    public function testPersistEnableOfferWhenOfferIsActive(): void
    {
        $data = (new OfferDto())->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1');

        $this->updateOfferService
            ->enableEditing('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willThrow(InvalidOfferStatusException::class)
            ->shouldBeCalled();

        $this->expectException(AccessDeniedHttpException::class);
        $this->persister->persist($data, ['item_operation_name' => OfferDto::OPERATION_ENABLE]);
    }
}
