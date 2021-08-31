<?php

namespace App\Tests\Application\Order\DataTransformer;

use App\Application\Order\DataTransformer\BuyerDataTransformer;
use App\Domain\Client\ClientFacade;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Order\Model\Buyer;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BuyerDataTransformerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ClientFacade $clientFacadeMock;
    private BuyerDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->clientFacadeMock = $this->prophesize(ClientFacade::class);
        $this->dataTransformer = new BuyerDataTransformer($this->clientFacadeMock->reveal());
    }

    public function testCreateBuyerFromClientCardNumber(): void
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
        $this->clientFacadeMock
            ->getClientByCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->willReturn($client);

        $result = $this->dataTransformer->createBuyerFromClientCardNumber('3da8b78de7732860e770d2a0a17b7b82');
        $this->assertInstanceOf(Buyer::class, $result);
    }
}
