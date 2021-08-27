<?php

namespace App\Tests\Application\Client\Service;

use App\Application\Client\Exception\CardNumberGenerationFailedException;
use App\Application\Client\Service\CardNumberGenerator;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Repository\ClientRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CardNumberGeneratorTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ClientRepository $repositoryMock;
    private CardNumberGenerator $generator;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->prophesize(ClientRepository::class);
        $this->generator = new CardNumberGenerator($this->repositoryMock->reveal());
    }

    public function testSuccessfulGenerateCardNumber(): void
    {
        $this->repositoryMock
            ->getClientByCardNumber(Argument::type('string'))
            ->willReturn(null)
            ->shouldBeCalledTimes(1);
        $cardNumber = $this->generator->generateNumber();
        $this->assertNotEmpty($cardNumber);
    }

    public function testFailedGenerateCardNumber(): void
    {
        $client = $this->prophesize(Client::class);
        $this->repositoryMock
            ->getClientByCardNumber(Argument::type('string'))
            ->willReturn($client->reveal())
            ->shouldBeCalledTimes(10);
        $this->expectException(CardNumberGenerationFailedException::class);
        $this->generator->generateNumber();
    }
}
