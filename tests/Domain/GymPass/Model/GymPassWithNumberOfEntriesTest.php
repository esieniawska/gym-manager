<?php

namespace App\Tests\Domain\GymPass\Model;

use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymEntering;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class GymPassWithNumberOfEntriesTest extends TestCase
{
    public function testCanUsePassWhenNoEntries(): void
    {
        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            (new \DateTimeImmutable())->setTime(0, 0),
            new NumberOfEntries(3)
        );

        $this->assertTrue($gymPass->canUsePass());
    }

    public function testCanUsePassBeforeStart(): void
    {
        $startDate = (new \DateTimeImmutable())
            ->setTime(0, 0)
            ->add(new \DateInterval('P2D'));

        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $startDate,
            new NumberOfEntries(3)
        );

        $this->assertFalse($gymPass->canUsePass());
    }

    public function testAddGymEnteringBeforeStart(): void
    {
        $startDate = (new \DateTimeImmutable())
            ->setTime(0, 0)
            ->add(new \DateInterval('P2D'));

        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $startDate,
            new NumberOfEntries(3)
        );

        $this->expectException(InactiveGymPassException::class);
        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));
    }

    public function testSuccessfulAddGymEntering(): void
    {
        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            (new \DateTimeImmutable())->setTime(0, 0),
            new NumberOfEntries(3)
        );

        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));
        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));
        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));

        $this->assertCount(3, $gymPass->getGymEntering());
    }

    public function testAddGymEnteringWhenTooMoreGymEntering(): void
    {
        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            (new \DateTimeImmutable())->setTime(0, 0),
            new NumberOfEntries(3)
        );

        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));
        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));
        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));

        $this->expectException(InactiveGymPassException::class);
        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));

        $this->assertCount(3, $gymPass->getGymEntering());
    }
}
