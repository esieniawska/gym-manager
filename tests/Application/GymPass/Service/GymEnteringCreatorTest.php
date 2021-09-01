<?php

namespace App\Tests\Application\GymPass\Service;

use App\Application\GymPass\Dto\AddGymEnteringDto;
use App\Application\GymPass\Exception\GymPassNotFoundException;
use App\Application\GymPass\Exception\InvalidGymPassClientException;
use App\Application\GymPass\Service\GymEnteringCreator;
use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class GymEnteringCreatorTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GymPassRepository $repositoryMock;
    private GymEnteringCreator $creator;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->prophesize(GymPassRepository::class);
        $this->creator = new GymEnteringCreator(
            $this->repositoryMock->reveal()
        );
    }

    public function testCreateWhenGymPassNotFound(): void
    {
        $this->repositoryMock->getGymPass(Argument::type(Uuid::class))->willReturn(null);

        $dto = new AddGymEnteringDto(
            '3da8b78de7732860e770d2a0a17b7b82',
            '0760bc37-30a5-446a-b129-90403382827b'
        );

        $this->expectException(GymPassNotFoundException::class);
        $this->creator->create($dto);
    }

    public function testCreateWhenGymPassHasOtherClient(): void
    {
        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('0760bc37-30a5-446a-b129-90403382827b'),
            new Client(new CardNumber('f9e33d75d79bf46e4873b4f920626888')),
            new \DateTimeImmutable(),
            new NumberOfEntries(4)
        );
        $this->repositoryMock->getGymPass(Argument::type(Uuid::class))->willReturn($gymPass);

        $dto = new AddGymEnteringDto(
            '3da8b78de7732860e770d2a0a17b7b82',
            '0760bc37-30a5-446a-b129-90403382827b'
        );

        $this->expectException(InvalidGymPassClientException::class);
        $this->creator->create($dto);
    }

    public function testSuccessfulCreateGymEntering(): void
    {
        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('0760bc37-30a5-446a-b129-90403382827b'),
            new Client(new CardNumber('f9e33d75d79bf46e4873b4f920626888')),
            new \DateTimeImmutable(),
            new NumberOfEntries(4)
        );
        $this->repositoryMock->getGymPass(Argument::type(Uuid::class))->willReturn($gymPass);

        $dto = new AddGymEnteringDto(
            'f9e33d75d79bf46e4873b4f920626888',
            '0760bc37-30a5-446a-b129-90403382827b'
        );

        $this->repositoryMock->updateGymPassEntries(Argument::type(GymPass::class))->shouldBeCalled();
        $this->creator->create($dto);
    }
}
