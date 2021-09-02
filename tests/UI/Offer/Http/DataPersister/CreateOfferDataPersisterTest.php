<?php

namespace App\Tests\UI\Offer\Http\DataPersister;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Service\CreateOfferService;
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
    private CreateOfferDataPersister $persister;

    protected function setUp(): void
    {
        $this->createOfferServiceMock = $this->prophesize(CreateOfferService::class);
        $this->createOfferDtoFactoryMock = $this->prophesize(CreateOfferDtoFactory::class);
        $this->persister = new CreateOfferDataPersister(
            $this->createOfferServiceMock->reveal(),
            $this->createOfferDtoFactoryMock->reveal()
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
        $this->createOfferServiceMock->create($dto)->shouldBeCalled();

        $this->persister->persist($data);
    }
}
