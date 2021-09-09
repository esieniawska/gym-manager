<?php

namespace App\Tests\UI\GymPass\Http\DataPersister;

use App\Application\GymPass\Dto\AddGymEnteringDto;
use App\Application\GymPass\Exception\GymPassNotFoundException;
use App\Application\GymPass\Exception\InvalidGymPassClientException;
use App\Application\GymPass\Service\GymEnteringCreator;
use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\UI\GymPass\Http\DataPersister\CreateGymEnteringDataPersister;
use App\UI\GymPass\Http\Dto\GymEnteringForm;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateGymEnteringDataPersisterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|GymEnteringCreator $enteringCreatorMock;
    private CreateGymEnteringDataPersister $persister;

    protected function setUp(): void
    {
        $this->enteringCreatorMock = $this->prophesize(GymEnteringCreator::class);
        $this->persister = new CreateGymEnteringDataPersister($this->enteringCreatorMock->reveal());
    }

    public function testSuccessfulPersist(): void
    {
        $this->enteringCreatorMock->create(Argument::type(AddGymEnteringDto::class))->shouldBeCalled();
        $data = new GymEnteringForm(
            'caabacb3554c96008ba346a61d1839fa',
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1'
        );
        $this->persister->persist($data);
    }

    public function testPersistWhenGymPassNotFound(): void
    {
        $this->enteringCreatorMock
            ->create(Argument::type(AddGymEnteringDto::class))
            ->willThrow(GymPassNotFoundException::class);
        $data = new GymEnteringForm(
            'caabacb3554c96008ba346a61d1839fa',
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1'
        );
        $this->expectException(NotFoundHttpException::class);
        $this->persister->persist($data);
    }

    public function testPersistWhenInvalidClient(): void
    {
        $this->enteringCreatorMock
            ->create(Argument::type(AddGymEnteringDto::class))
            ->willThrow(InvalidGymPassClientException::class);
        $data = new GymEnteringForm(
            'caabacb3554c96008ba346a61d1839fa',
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1'
        );
        $this->expectException(AccessDeniedHttpException::class);
        $this->persister->persist($data);
    }

    public function testPersistWhenInactiveGym(): void
    {
        $this->enteringCreatorMock
            ->create(Argument::type(AddGymEnteringDto::class))
            ->willThrow(InactiveGymPassException::class);
        $data = new GymEnteringForm(
            'caabacb3554c96008ba346a61d1839fa',
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1'
        );
        $this->expectException(BadRequestHttpException::class);
        $this->persister->persist($data);
    }
}
