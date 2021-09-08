<?php

namespace App\Tests\UI\Order\Http\DataPersister;

use App\Application\Offer\Exception\OfferNotFoundException;
use App\Application\Order\Dto\CreateOrderDto;
use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Service\CreateOrderService;
use App\UI\Order\Http\DataPersister\CreateOrderDataPersister;
use App\UI\Order\Http\Dto\CreateOrderForm;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateOrderDataPersisterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|CreateOrderService $createOrderServiceMock;
    private CreateOrderDataPersister $persister;

    protected function setUp(): void
    {
        $this->createOrderServiceMock = $this->prophesize(CreateOrderService::class);
        $this->persister = new CreateOrderDataPersister($this->createOrderServiceMock->reveal());
    }

    public function testSuccessfulPersist(): void
    {
        $this->createOrderServiceMock->create(Argument::type(CreateOrderDto::class))->shouldBeCalled();
        $data = new CreateOrderForm(
            'caabacb3554c96008ba346a61d1839fa',
            'fb495950-48f5-44e2-8c1f-240799c6340a',
            new \DateTimeImmutable()
        );
        $this->persister->persist($data);
    }

    public function testPersistWhenOrderFailedException(): void
    {
        $this->createOrderServiceMock
            ->create(Argument::type(CreateOrderDto::class))
            ->willThrow(OrderFailedException::class)
            ->shouldBeCalled();
        $data = new CreateOrderForm(
            'caabacb3554c96008ba346a61d1839fa',
            'fb495950-48f5-44e2-8c1f-240799c6340a',
            new \DateTimeImmutable()
        );

        $this->expectException(BadRequestHttpException::class);
        $this->persister->persist($data);
    }

    public function testPersistWhenOfferNotFound(): void
    {
        $this->createOrderServiceMock
            ->create(Argument::type(CreateOrderDto::class))
            ->willThrow(OfferNotFoundException::class)
            ->shouldBeCalled();
        $data = new CreateOrderForm(
            'caabacb3554c96008ba346a61d1839fa',
            'fb495950-48f5-44e2-8c1f-240799c6340a',
            new \DateTimeImmutable()
        );

        $this->expectException(NotFoundHttpException::class);
        $this->persister->persist($data);
    }
}
