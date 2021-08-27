<?php

namespace App\Tests\UI\Client\Http\DataProvider;

use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Service\GetClientService;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Shared\ValueObject\Gender;
use App\UI\Client\Converter\ClientDtoCollectionConverter;
use App\UI\Client\Http\DataProvider\ClientCollectionDataProvider;
use App\UI\Client\Http\Dto\ClientDto as HttpClientDto;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ClientCollectionDataProviderTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GetClientService $clientServiceMock;
    private ObjectProphecy|ClientDtoCollectionConverter $converterMock;
    private ClientCollectionDataProvider $dataProvider;

    protected function setUp(): void
    {
        $this->clientServiceMock = $this->prophesize(GetClientService::class);
        $this->converterMock = $this->prophesize(ClientDtoCollectionConverter::class);
        $this->dataProvider = new ClientCollectionDataProvider(
            $this->clientServiceMock->reveal(),
            $this->converterMock->reveal()
        );
    }

    public function testGetCollection(): void
    {
        $clientDto = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@example.com'
        );

        $this->clientServiceMock->getAllClients()->willReturn(new ArrayCollection([$clientDto]));

        $httpDto = new HttpClientDto(
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@example.com'
        );
        $httpDto
            ->setStatus(ClientStatus::ACTIVE)
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setCardNumber('3da8b78de7732860e770d2a0a17b7b82');
        $this->converterMock
            ->createHttpFromApplicationDtoCollection(Argument::type(ArrayCollection::class))
            ->willReturn(new ArrayCollection([$httpDto]));

        $result = $this->dataProvider->getCollection(HttpClientDto::class);
        $this->assertEquals(1, $result->count());
        $this->assertEquals($httpDto, $result->first());
    }
}
