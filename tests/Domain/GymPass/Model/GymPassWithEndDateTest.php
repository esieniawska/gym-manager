<?php

namespace App\Tests\Domain\GymPass\Model;

use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class GymPassWithEndDateTest extends TestCase
{
    public function testCanUsePassWhenThisSameDateAsStartDate(): void
    {
        $endDate = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            new \DateTimeImmutable(),
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
}
