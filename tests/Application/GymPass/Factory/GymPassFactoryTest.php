<?php

namespace App\Tests\Application\GymPass\Factory;

use App\Application\GymPass\Exception\InvalidOrderCreatedEventException;
use App\Application\GymPass\Factory\GymPassFactory;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Order\Event\OrderCreated;
use App\Domain\Order\Event\OrderForTicketNumberOfDaysCreated;
use App\Domain\Order\Event\OrderForTicketNumberOfEntriesCreated;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GymPassFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testCreateGymPassWithNumberOfEntries(): void
    {
        $event = new OrderForTicketNumberOfEntriesCreated(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            new \DateTimeImmutable('20-11-2020'),
            3
        );

        $repository = $this->prophesize(GymPassRepository::class);
        $repository->nextIdentity()->willReturn(new Uuid('77c9e11a-74a6-4d02-8ae5-3d41c54dac98'));
        $factory = new GymPassFactory($repository->reveal());

        $result = $factory->createGymPassFromEvent($event);
        $this->assertInstanceOf(GymPassWithNumberOfEntries::class, $result);
    }

    public function testCreateGymPassWithEndDate(): void
    {
        $event = new OrderForTicketNumberOfDaysCreated(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            new \DateTimeImmutable('20-11-2020'),
            3
        );

        $repository = $this->prophesize(GymPassRepository::class);
        $repository->nextIdentity()->willReturn(new Uuid('77c9e11a-74a6-4d02-8ae5-3d41c54dac98'));
        $factory = new GymPassFactory($repository->reveal());

        $result = $factory->createGymPassFromEvent($event);
        $this->assertInstanceOf(GymPassWithEndDate::class, $result);
        $this->assertEquals('23-11-2020', $result->getEndDate()->format('d-m-Y'));
    }

    public function testTryCreateGymPassWhenInvalidEvent(): void
    {
        $event = $this->prophesize(OrderCreated::class);
        $repository = $this->prophesize(GymPassRepository::class);
        $factory = new GymPassFactory($repository->reveal());
        $this->expectException(InvalidOrderCreatedEventException::class);
        $factory->createGymPassFromEvent($event->reveal());
    }
}
