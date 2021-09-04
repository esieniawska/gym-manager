<?php

namespace App\Tests\UI\Offer\Http\DataProvider;

use App\Application\Offer\Dto\Filter;
use App\Application\Offer\Dto\OfferDto as ApplicationDto;
use App\Application\Offer\Service\GetOfferService;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\ValueObject\Gender;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\DataProvider\OfferCollectionDataProvider;
use App\UI\Offer\Http\Dto\OfferDto;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class OfferCollectionDataProviderTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GetOfferService $getOfferServiceMock;
    private ObjectProphecy|OfferDtoConverter $offerDtoConverterMock;
    private OfferCollectionDataProvider $dataProvider;

    protected function setUp(): void
    {
        $this->getOfferServiceMock = $this->prophesize(GetOfferService::class);
        $this->offerDtoConverterMock = $this->prophesize(OfferDtoConverter::class);
        $this->dataProvider = new OfferCollectionDataProvider(
            $this->getOfferServiceMock->reveal(),
            $this->offerDtoConverterMock->reveal()
        );
    }

    public function testGetCollection(): void
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
        $this->getOfferServiceMock->getAllOffer(Argument::type(Filter::class))
            ->willReturn(new ArrayCollection([$dto]));

        $httpDto = (new OfferDto())
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setType(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES)
            ->setQuantity(3)
            ->setPrice(1.02)
            ->setName('offer-name')
            ->setGender(Gender::MALE)
            ->setStatus(OfferStatus::ACTIVE);
        $this->offerDtoConverterMock->createHttpFromApplicationDtoCollection(Argument::type(ArrayCollection::class))
            ->willReturn(new ArrayCollection([$httpDto]));

        $result = $this->dataProvider->getCollection(OfferDto::class, 'get', ['search_filter' => ['name' => 'offer']]);
        $this->assertInstanceOf(OfferDto::class, $result->first());
    }
}
