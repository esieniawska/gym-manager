<?php

namespace App\Tests\Application\GymPass\Service;

use App\Application\GymPass\Dto\GymPassLockingDto;
use App\Application\GymPass\Dto\GymPassLockingResult;
use App\Application\GymPass\Exception\GymPassNotFoundException;
use App\Application\GymPass\Exception\InvalidGymPassTypeException;
use App\Application\GymPass\Service\GymPassLockingService;
use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class GymPassLockingServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GymPassRepository $repositoryMock;
    private GymPassLockingService $gymPassLocking;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->prophesize(GymPassRepository::class);
        $this->gymPassLocking = new GymPassLockingService(
            $this->repositoryMock->reveal()
        );
    }

    public function testLockGymPassWhenPassNotFound(): void
    {
        $this->repositoryMock->getGymPass(Argument::type(Uuid::class))->willReturn(null);

        $dto = new GymPassLockingDto(
            '0760bc37-30a5-446a-b129-90403382827b',
            5
        );

        $this->expectException(GymPassNotFoundException::class);
        $this->gymPassLocking->lockGymPass($dto);
    }

    public function testLockGymPassWhenPassHasInvalidType(): void
    {
        $gymPass = $this->prophesize(GymPassWithNumberOfEntries::class);
        $this->repositoryMock->getGymPass(Argument::type(Uuid::class))
            ->willReturn($gymPass->reveal());

        $dto = new GymPassLockingDto(
            '0760bc37-30a5-446a-b129-90403382827b',
            5
        );

        $this->expectException(InvalidGymPassTypeException::class);
        $this->gymPassLocking->lockGymPass($dto);
    }

    public function testSuccessfulLockGymPass(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $endDate = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $gymPass = new GymPassWithEndDate(
            new Uuid('136a74eb-0468-466b-8dd4-c5149f284223'),
            new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')),
            $startDate,
            $endDate
        );
        $this->repositoryMock->getGymPass(Argument::type(Uuid::class))
            ->willReturn($gymPass);

        $dto = new GymPassLockingDto(
            '0760bc37-30a5-446a-b129-90403382827b',
            5
        );

        $this->repositoryMock
            ->updateGymPassDates(Argument::type(GymPass::class))
            ->shouldBeCalled();

        $result = $this->gymPassLocking->lockGymPass($dto);
        $this->assertInstanceOf(GymPassLockingResult::class, $result);

        $newEndDate = $endDate->add(new \DateInterval('P4D'));
        $endLockDate = (new \DateTimeImmutable())->add(new \DateInterval('P4D'));
        $this->assertEquals($newEndDate->format('Y-m-d'), $result->getEndDate()->format('Y-m-d'));
        $this->assertEquals((new \DateTimeImmutable())->format('Y-m-d'), $result->getLockStartDate()->format('Y-m-d'));
        $this->assertEquals($endLockDate->format('Y-m-d'), $result->getLockEndDate()->format('Y-m-d'));
    }
}
