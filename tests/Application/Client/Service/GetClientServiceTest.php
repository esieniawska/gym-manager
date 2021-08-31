<?php

namespace App\Tests\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Application\Client\Service\GetClientService;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class GetClientServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ClientRepository $clientRepositoryMock;
    private ObjectProphecy|ClientDtoAssembler $clientDtoAssemblerMock;
    private GetClientService $clientService;

    protected function setUp(): void
    {
        $this->clientRepositoryMock = $this->prophesize(ClientRepository::class);
        $this->clientDtoAssemblerMock = $this->prophesize(ClientDtoAssembler::class);
        $this->clientService = new GetClientService(
            $this->clientRepositoryMock->reveal(),
            $this->clientDtoAssemblerMock->reveal()
        );
    }

    public function testGetClientByIdWhenClientNotFound(): void
    {
        $this->clientRepositoryMock
            ->getClientById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'))
            ->willReturn(null);

        $this->expectException(ClientNotFoundException::class);
        $this->clientService->getClientById('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
    }

    public function testGetClientByIdWhenClientExist(): void
    {
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            Gender::MALE(),
            ClientStatus::ACTIVE(),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );
        $this->clientRepositoryMock
            ->getClientById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'))
            ->willReturn($client);

        $dto = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@example.com'
        );
        $this->clientDtoAssemblerMock->assembleDomainObjectToDto($client)->willReturn($dto);

        $result = $this->clientService->getClientById('7d24cece-b0c6-4657-95d5-31180ebfc8e1');
        $this->assertEquals($dto, $result);
    }

    public function testGetAllClients(): void
    {
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            Gender::MALE(),
            ClientStatus::ACTIVE(),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );
        $this->clientRepositoryMock
            ->getAll()
            ->willReturn(new ArrayCollection([$client]));

        $dto = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@example.com'
        );
        $this->clientDtoAssemblerMock
            ->assembleAll(Argument::type(ArrayCollection::class))
            ->willReturn(new ArrayCollection([$dto]));

        $result = $this->clientService->getAllClients();
        $this->assertEquals(1, $result->count());
        $this->assertEquals($dto, $result->first());
    }
}
