<?php

namespace App\Tests\UI\GymPass\Http\DataPersister;

use App\Application\GymPass\Dto\GymPassLockingDto;
use App\Application\GymPass\Dto\GymPassLockingResult;
use App\Application\GymPass\Exception\GymPassNotFoundException;
use App\Application\GymPass\Exception\InvalidGymPassTypeException;
use App\Application\GymPass\Service\GymPassLockingService;
use App\UI\GymPass\Http\DataPersister\GymPassLockingDataPersister;
use App\UI\GymPass\Http\Dto\GymPassLockingForm;
use App\UI\GymPass\Http\Dto\GymPassLockingOutput;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GymPassLockingDataPersisterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GymPassLockingService $lockingServiceMock;
    private GymPassLockingDataPersister $persister;

    protected function setUp(): void
    {
        $this->lockingServiceMock = $this->prophesize(GymPassLockingService::class);
        $this->persister = new GymPassLockingDataPersister($this->lockingServiceMock->reveal());
    }

    public function testSuccessfulPersist(): void
    {
        $newEndDate = (new \DateTimeImmutable())->add(new \DateInterval('P6D'));
        $endLockDate = (new \DateTimeImmutable())->add(new \DateInterval('P4D'));

        $this->lockingServiceMock
            ->lockGymPass(Argument::type(GymPassLockingDto::class))
            ->willReturn(new GymPassLockingResult(
                $newEndDate,
                new \DateTimeImmutable(),
                $endLockDate
            ))
            ->shouldBeCalled();

        $data = new GymPassLockingForm(5, '0760bc37-30a5-446a-b129-90403382827b');
        $result = $this->persister->persist($data);
        $this->assertInstanceOf(GymPassLockingOutput::class, $result);
    }

    public function testFailedPersistWhenGymPassNotFound(): void
    {
        $this->lockingServiceMock
            ->lockGymPass(Argument::type(GymPassLockingDto::class))
            ->willThrow(GymPassNotFoundException::class)
            ->shouldBeCalled();

        $data = new GymPassLockingForm(5, '0760bc37-30a5-446a-b129-90403382827b');
        $this->expectException(NotFoundHttpException::class);
        $this->persister->persist($data);
    }

    public function testFailedPersistWhenInvalidType(): void
    {
        $this->lockingServiceMock
            ->lockGymPass(Argument::type(GymPassLockingDto::class))
            ->willThrow(InvalidGymPassTypeException::class)
            ->shouldBeCalled();

        $data = new GymPassLockingForm(5, '0760bc37-30a5-446a-b129-90403382827b');
        $this->expectException(BadRequestHttpException::class);
        $this->persister->persist($data);
    }
}
