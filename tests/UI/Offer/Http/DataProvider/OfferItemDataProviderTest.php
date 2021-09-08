<?php

namespace App\Tests\UI\Offer\Http\DataProvider;

use App\Application\Offer\Dto\OfferDto as ApplicationDto;
use App\Application\Offer\Exception\OfferNotFoundException;
use App\Application\Offer\Service\GetOfferService;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Gender;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\DataProvider\OfferItemDataProvider;
use App\UI\Offer\Http\Dto\OfferDto;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OfferItemDataProviderTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GetOfferService $getOfferServiceMock;
    private ObjectProphecy|OfferDtoConverter $offerDtoConverterMock;
    private OfferItemDataProvider $dataProvider;

    protected function setUp(): void
    {
        $this->getOfferServiceMock = $this->prophesize(GetOfferService::class);
        $this->offerDtoConverterMock = $this->prophesize(OfferDtoConverter::class);
        $this->dataProvider = new OfferItemDataProvider(
            $this->getOfferServiceMock->reveal(),
            $this->offerDtoConverterMock->reveal()
        );
    }

    public function testGetItemWhenOfferNotFound(): void
    {
        $this->getOfferServiceMock->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willThrow(OfferNotFoundException::class);
        $this->expectException(NotFoundHttpException::class);
        $this->dataProvider->getItem(OfferDto::class, '7d24cece-b0c6-4657-95d5-31180ebfc8e1');
    }

    public function testGetItemWhenWrongUuid(): void
    {
        $this->getOfferServiceMock->getOfferById('7d24cece')
            ->willThrow(InvalidValueException::class);
        $this->expectException(BadRequestHttpException::class);
        $this->dataProvider->getItem(OfferDto::class, '7d24cece');
    }

    public function testGetItemWhenOfferExist(): void
    {
        $dto = new ApplicationDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            ApplicationDto::TYPE_NUMBER_OF_ENTRIES,
            'offer-name',
            1.02,
            OfferStatus::ACTIVE,
            3,
            Gender::MALE
        );
        $this->getOfferServiceMock->getOfferById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn($dto);

        $httpDto = (new OfferDto())
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setType(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES)
            ->setQuantity(3)
            ->setPrice(1.02)
            ->setName('offer-name')
            ->setGender(Gender::MALE)
            ->setStatus(OfferStatus::ACTIVE);
        $this->offerDtoConverterMock->createHttpFromApplicationDto($dto)
            ->willReturn($httpDto);

        $result = $this->dataProvider->getItem(OfferDto::class, '7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertInstanceOf(OfferDto::class, $result);
    }
}
