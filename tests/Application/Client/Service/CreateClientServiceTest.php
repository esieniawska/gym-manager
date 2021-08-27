<?php

namespace App\Tests\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Dto\CreateClientDto;
use App\Application\Client\Service\CardNumberGenerator;
use App\Application\Client\Service\CreateClientService;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateClientServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ClientRepository $repositoryMock;
    private ObjectProphecy|CardNumberGenerator $cardNumberGeneratorMock;
    private ObjectProphecy|ClientDtoAssembler  $assemblerMock;
    private CreateClientService $clientService;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->prophesize(ClientRepository::class);
        $this->cardNumberGeneratorMock = $this->prophesize(CardNumberGenerator::class);
        $this->assemblerMock = $this->prophesize(ClientDtoAssembler::class);
        $this->clientService = new CreateClientService(
            $this->repositoryMock->reveal(),
            $this->cardNumberGeneratorMock->reveal(),
            $this->assemblerMock->reveal()
        );
    }

    public function testCreateClient(): void
    {
        $this->repositoryMock->nextIdentity()->willReturn(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));
        $this->cardNumberGeneratorMock->generateNumber()->willReturn('3da8b78de7732860e770d2a0a17b7b82');
        $this->repositoryMock->addClient(Argument::type(Client::class))->shouldBeCalled();

        $assemblerResult = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@test.com'
        );

        $this->assemblerMock->assembleDomainObjectToDto(Argument::type(Client::class))
            ->willReturn($assemblerResult);

        $createClientDto = new CreateClientDto(
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@test.com'
        );
        $result = $this->clientService->createClient($createClientDto);
        $this->assertEquals('7d24cece-b0c6-4657-95d5-31180ebfc8e1', $result->getId());
        $this->assertEquals('3da8b78de7732860e770d2a0a17b7b82', $result->getCardNumber());
    }
}
