<?php

namespace App\Tests\Domain\GymPass\Model;

use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class GymPassWithEndDateTest extends TestCase
{
    public function testCanUsePassWhenThisSameDateAsStartDate(): void
    {
        $baseDate = (new \DateTimeImmutable())->setTime(0, 0);
        $endDate = $baseDate->add(new \DateInterval('P2D'));
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $baseDate,
            $endDate
        );

        $this->assertTrue($gymPass->canUsePass());
    }

    public function testCanUsePassWhenThisSameDateAsEndDate(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $startDate,
            new \DateTimeImmutable()
        );

        $this->assertTrue($gymPass->canUsePass());
    }

    public function testCanUsePassWhenDateAfterEndDate(): void
    {
        $date = (new \DateTimeImmutable())->modify('-1 day');
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $date,
            $date
        );

        $this->assertFalse($gymPass->canUsePass());
    }

    public function testCanUsePassWhenDateBeforeStartDate(): void
    {
        $date = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $date,
            $date
        );

        $this->assertFalse($gymPass->canUsePass());
    }

    public function testFailedLockGymPassWhenGymPassIsLocked(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $endDate = (new \DateTimeImmutable())->add(new \DateInterval('P3D'));
        $lockStartDate = new \DateTimeImmutable();
        $lockEndDate = (new \DateTimeImmutable())->add(new \DateInterval('P1D'));
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $startDate,
            $endDate,
            $lockStartDate,
            $lockEndDate
        );

        $this->expectException(InactiveGymPassException::class);
        $gymPass->lockGymPass(new NumberOfDays(5));
    }

    public function testSuccessfulLockGymPass(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $endDate = (new \DateTimeImmutable())
            ->setTime(0, 0)
            ->add(new \DateInterval('P2D'));
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $startDate,
            $endDate
        );

        $gymPass->lockGymPass(new NumberOfDays(5));
        $newEndDate = $endDate->add(new \DateInterval('P5D'));
        $endLockDate = (new \DateTimeImmutable())->add(new \DateInterval('P4D'));

        $this->assertEquals($newEndDate->format('Y-m-d'), $gymPass->getEndDate()->format('Y-m-d'));
        $this->assertEquals((new \DateTimeImmutable())->format('Y-m-d'), $gymPass->getLockStartDate()->format('Y-m-d'));
        $this->assertEquals($endLockDate->format('Y-m-d'), $gymPass->getLockEndDate()->format('Y-m-d'));
    }

    public function testLockGymPassForOneDay(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $endDate = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $startDate,
            $endDate
        );

        $gymPass->lockGymPass(new NumberOfDays(1));

        $newEndDate = $endDate->add(new \DateInterval('P1D'));
        $this->assertEquals($newEndDate->format('Y-m-d'), $gymPass->getEndDate()->format('Y-m-d'));
        $this->assertEquals((new \DateTimeImmutable())->format('Y-m-d'), $gymPass->getLockStartDate()->format('Y-m-d'));
        $this->assertEquals((new \DateTimeImmutable())->format('Y-m-d'), $gymPass->getLockEndDate()->format('Y-m-d'));
    }

    public function testCanUsePassWhenGymPassIsLocked(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $endDate = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $startDate,
            $endDate
        );
        $this->assertTrue($gymPass->canUsePass());
        $gymPass->lockGymPass(new NumberOfDays(5));
        $this->assertFalse($gymPass->canUsePass());
    }
}
