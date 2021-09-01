<?php

namespace App\Tests\Application\GymPass\Service;

use App\Application\GymPass\Factory\GymPassFactory;
use App\Application\GymPass\Service\GymPassCreator;
use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Order\Event\OrderForTicketNumberOfEntriesCreated;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class GymPassCreatorTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GymPassFactory $gymPassFactoryMock;
    private ObjectProphecy|GymPassRepository $gymPassRepositoryMock;
    private GymPassCreator $creator;

    protected function setUp(): void
    {
        $this->gymPassFactoryMock = $this->prophesize(GymPassFactory::class);
        $this->gymPassRepositoryMock = $this->prophesize(GymPassRepository::class);
        $this->creator = new GymPassCreator(
            $this->gymPassFactoryMock->reveal(),
            $this->gymPassRepositoryMock->reveal()
        );
    }

    public function testCreate(): void
    {
        $event = new OrderForTicketNumberOfEntriesCreated(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            new \DateTimeImmutable(),
            3
        );

        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('0760bc37-30a5-446a-b129-90403382827b'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            new \DateTimeImmutable(),
            new NumberOfEntries(4)
        );

        $this->gymPassFactoryMock
            ->createGymPassFromEvent($event)
            ->willReturn($gymPass);

        $this->gymPassRepositoryMock
            ->addGymPass($gymPass)
            ->shouldBeCalled();

        $this->creator->create($event);
    }
}
