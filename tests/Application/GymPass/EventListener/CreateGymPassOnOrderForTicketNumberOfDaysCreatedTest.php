<?php

namespace App\Tests\Application\GymPass\EventListener;

use App\Application\GymPass\EventListener\CreateGymPassOnOrderForTicketNumberOfDaysCreated;
use App\Application\GymPass\Service\GymPassCreator;
use App\Domain\Order\Event\OrderForTicketNumberOfDaysCreated;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateGymPassOnOrderForTicketNumberOfDaysCreatedTest extends TestCase
{
    use ProphecyTrait;

    public function testCreateGymPass(): void
    {
        $event = new OrderForTicketNumberOfDaysCreated(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            new \DateTimeImmutable(),
            3
        );
        $creatorMock = $this->prophesize(GymPassCreator::class);
        $creatorMock->create($event)->shouldBeCalled();
        $eventListener = new CreateGymPassOnOrderForTicketNumberOfDaysCreated($creatorMock->reveal());
        $eventListener($event);
    }
}
