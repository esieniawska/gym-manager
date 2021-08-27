<?php

namespace App\Tests\UI\Client\Http\DataPersister;

use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Dto\CreateClientDto;
use App\Application\Client\Service\CreateClientService;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Shared\ValueObject\Gender;
use App\UI\Client\Converter\ClientDtoCollectionConverter;
use App\UI\Client\Http\DataPersister\CreateClientDataPersister;
use App\UI\Client\Http\Dto\ClientDto as HttpClientDto;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateClientDataPersisterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|CreateClientService $clientServiceMock;
    private ObjectProphecy|ClientDtoCollectionConverter $converterMock;
    private CreateClientDataPersister $persister;

    protected function setUp(): void
    {
        $this->clientServiceMock = $this->prophesize(CreateClientService::class);
        $this->converterMock = $this->prophesize(ClientDtoCollectionConverter::class);
        $this->persister = new CreateClientDataPersister(
            $this->clientServiceMock->reveal(),
            $this->converterMock->reveal()
        );
    }

    public function testPersist(): void
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
        $this->clientServiceMock
            ->createClient(Argument::type(CreateClientDto::class))
            ->willReturn($clientDto);
        $requestData = (new HttpClientDto())
            ->setFirstName('Joe')
            ->setLastName('Smith')
            ->setGender(Gender::MALE)
            ->setPhoneNumber('123456789')
            ->setEmail('test@example.com');
        $httpDto = clone $requestData;
        $httpDto
            ->setStatus(ClientStatus::ACTIVE)
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setCardNumber('3da8b78de7732860e770d2a0a17b7b82');

        $this->converterMock
            ->createHttpFromApplicationDto($clientDto)
            ->willReturn($httpDto);

        $result = $this->persister->persist($requestData);
        $this->assertEquals($httpDto, $result);
    }
}
