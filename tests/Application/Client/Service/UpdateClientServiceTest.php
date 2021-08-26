<?php

namespace App\Tests\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Dto\UpdateClientDto;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Application\Client\Service\UpdateClientService;
use App\Domain\Client\Entity\CardNumber;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Entity\ClientStatus;
use App\Domain\Client\Entity\PhoneNumber;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\Gender;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class UpdateClientServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ClientRepository $clientRepositoryMock;
    private ObjectProphecy|ClientDtoAssembler $assemblerMock;
    private ObjectProphecy|UpdateClientService $clientService;

    protected function setUp(): void
    {
        $this->clientRepositoryMock = $this->prophesize(ClientRepository::class);
        $this->assemblerMock = $this->prophesize(ClientDtoAssembler::class);
        $this->clientService = new UpdateClientService(
            $this->clientRepositoryMock->reveal(),
            $this->assemblerMock->reveal()
        );
    }

    public function testFailedUpdateClient(): void
    {
        $this->clientRepositoryMock
            ->getClientById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'))
            ->willReturn(null);

        $this->expectException(ClientNotFoundException::class);
        $updateDto = new UpdateClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            'Joe',
            'Smith',
            Gender::MALE,
            ClientStatus::ACTIVE,
            null,
            null
        );
        $this->clientService->updateClient($updateDto);
    }

    public function testSuccessfulUpdateClient(): void
    {
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new Gender(Gender::FEMALE),
            new ClientStatus(ClientStatus::NOT_ACTIVE),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );
        $this->clientRepositoryMock
            ->getClientById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'))
            ->willReturn($client);

        $this->clientRepositoryMock->updateClient(Argument::type(Client::class))->shouldBeCalled();
        $this->assemblerMock
            ->assembleDomainObjectToDto($client)
            ->willReturn(new ClientDto(
                '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
                '3da8b78de7732860e770d2a0a17b7b82',
                ClientStatus::ACTIVE,
                'Joe',
                'Shmith',
                Gender::MALE,
                '123456789',
                null
            ));

        $updateDto = new UpdateClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            'Joe',
            'Smith',
            Gender::MALE,
            ClientStatus::ACTIVE,
            '123456789',
            null
        );
        $this->clientService->updateClient($updateDto);
    }
}
