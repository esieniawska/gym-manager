<?php

namespace App\Tests\Domain\Order\Specification;

use App\Domain\Order\Exception\InvalidOrderItemException;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Specification\OrderItemSpecification;
use App\Domain\Order\Specification\OrderItemSpecificationValidator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class OrderItemSpecificationValidatorTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OrderItemSpecification $specificationMock;
    private OrderItemSpecificationValidator $validator;

    protected function setUp(): void
    {
        $this->specificationMock = $this->prophesize(OrderItemSpecification::class);
        $this->validator = new OrderItemSpecificationValidator($this->specificationMock->reveal(), 'Invalid order');
    }

    public function testCheckIsValidOrderItemWhenOrderIsNotValid(): void
    {
        $this->specificationMock
            ->isSatisfiedBy(Argument::type(OrderItem::class))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->expectException(InvalidOrderItemException::class);
        $orderItem = $this->prophesize(TicketWithNumberOfDaysAndGender::class);
        $this->validator->checkIsValidOrderItem($orderItem->reveal());
    }

    public function testCheckIsValidOrderItemWhenOrderIsValid(): void
    {
        $this->specificationMock
            ->isSatisfiedBy(Argument::type(OrderItem::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $orderItem = $this->prophesize(TicketWithNumberOfDaysAndGender::class);
        $this->validator->checkIsValidOrderItem($orderItem->reveal());
    }
}
