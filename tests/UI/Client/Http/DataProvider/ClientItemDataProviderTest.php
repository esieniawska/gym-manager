<?php

namespace App\Tests\UI\Client\Http\DataProvider;

use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Application\Client\Service\GetClientService;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\Gender;
use App\UI\Client\Converter\ClientDtoCollectionConverter;
use App\UI\Client\Http\DataProvider\ClientItemDataProvider;
use App\UI\Client\Http\Dto\ClientDto as HttpClientDto;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientItemDataProviderTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GetClientService $clientServiceMock;
    private ObjectProphecy|ClientDtoCollectionConverter $converterMock;
    private ClientItemDataProvider $dataProvider;

    protected function setUp(): void
    {
        $this->clientServiceMock = $this->prophesize(GetClientService::class);
        $this->converterMock = $this->prophesize(ClientDtoCollectionConverter::class);
        $this->dataProvider = new ClientItemDataProvider(
            $this->clientServiceMock->reveal(),
            $this->converterMock->reveal()
        );
    }

    public function testSuccessfulGetItem(): void
    {
        $clientDto = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE(),
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@example.com'
        );
        $this->clientServiceMock
            ->getClientById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn($clientDto);
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
        $this->converterMock->createHttpFromApplicationDto($clientDto)
            ->willReturn($httpDto);

        $result = $this->dataProvider->getItem(HttpClientDto::class, '7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertEquals($httpDto, $result);
    }

    public function testGetItemWhenWrongUuid(): void
    {
        $this->clientServiceMock
            ->getClientById('7d24cec')
            ->willThrow(InvalidValueException::class);

        $this->converterMock->createHttpFromApplicationDto(Argument::type(HttpClientDto::class))->shouldNotBeCalled();

        $this->expectException(BadRequestHttpException::class);
        $this->dataProvider->getItem(HttpClientDto::class, '7d24cec');
    }

    public function testGetItemWhenClientNotFound(): void
    {
        $this->clientServiceMock
            ->getClientById('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->willThrow(ClientNotFoundException::class);

        $this->converterMock->createHttpFromApplicationDto(Argument::type(HttpClientDto::class))->shouldNotBeCalled();

        $this->expectException(NotFoundHttpException::class);
        $this->dataProvider->getItem(HttpClientDto::class, '7d24cece-b0c6-4657-95d5-31180ebfc8e1');
    }
}
