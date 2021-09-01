<?php

namespace App\Tests\Domain\Client;

use App\Domain\Client\ClientFacade;
use App\Domain\Client\Exception\ClientNotFoundException;
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
    private ClientFacade $facade;

    protected function setUp(): void
    {
        $this->clientRepositoryMock = $this->prophesize(ClientRepository::class);
        $this->facade = new ClientFacade($this->clientRepositoryMock->reveal());
    }

    public function testGetClientByCardNumberWhenClientNotFound(): void
    {
        $this->clientRepositoryMock->getClientByCardNumber('card-number')->willReturn(null);
        $this->expectException(ClientNotFoundException::class);
        $this->facade->getClientByCardNumber('card-number');
    }

    public function testGetClientByCardNumberWhenClientExist(): void
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

        $result = $this->facade->getClientByCardNumber('3da8b78de7732860e770d2a0a17b7b82');
        $this->assertEquals($client, $result);
    }
}
