<?php

namespace App\Tests\Application\Client;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\ClientFacade;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Exception\ClientCanNotCreateOrderException;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ClientFacadeTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ClientRepository $clientRepositoryMock;
    private ObjectProphecy|ClientDtoAssembler $clientDtoAssemblerMock;
    private ClientFacade $facade;

    protected function setUp(): void
    {
        $this->clientRepositoryMock = $this->prophesize(ClientRepository::class);
        $this->clientDtoAssemblerMock = $this->prophesize(ClientDtoAssembler::class);

        $this->facade = new ClientFacade(
            $this->clientRepositoryMock->reveal(),
            $this->clientDtoAssemblerMock->reveal()
        );
    }

    public function testGetClientByCardNumberWhenClientNotFound(): void
    {
        $this->clientRepositoryMock->getClientByCardNumber('card-number')->willReturn(null);
        $this->expectException(ClientNotFoundException::class);
        $this->facade->getClientByCardNumberThatCanOrder('card-number');
    }

    public function testGetClientByCardNumberWhenClientExistAndCannotCreateOrder(): void
    {
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            Gender::FEMALE(),
            ClientStatus::NOT_ACTIVE(),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );
        $this->clientRepositoryMock
            ->getClientByCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->willReturn($client);

        $this->expectException(ClientCanNotCreateOrderException::class);
        $this->facade->getClientByCardNumberThatCanOrder('3da8b78de7732860e770d2a0a17b7b82');
    }

    public function testGetClientByCardNumberWhenClientExistAndCanCreateOrder(): void
    {
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            Gender::FEMALE(),
            ClientStatus::ACTIVE(),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );
        $this->clientRepositoryMock
            ->getClientByCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->willReturn($client);

        $dto = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::FEMALE,
            '123456789',
            'test@example.com'
        );
        $this->clientDtoAssemblerMock->assembleDomainObjectToDto($client)
            ->willReturn($dto);

        $result = $this->facade->getClientByCardNumberThatCanOrder('3da8b78de7732860e770d2a0a17b7b82');

        $this->assertEquals($dto, $result);
    }
}
