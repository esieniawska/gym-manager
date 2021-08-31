<?php

namespace App\Tests\Domain\Order\Validator;

use App\Domain\Order\Exception\InvalidBuyerException;
use App\Domain\Order\Exception\InvalidOrderItemException;
use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\BuyerStatus;
use App\Domain\Order\Model\NumberOfDays;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Model\TicketStatus;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Specification\OrderItemSpecificationValidator;
use App\Domain\Order\Specification\OrderItemSpecificationValidatorsFactory;
use App\Domain\Order\Validator\OrderValidator;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class OrderValidatorTest extends TestCase
{
    use ProphecyTrait;

    private OrderValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new OrderValidator(new OrderItemSpecificationValidatorsFactory());
    }

    public function testEnsureIsValidOrderWhenBuyerIsInactive(): void
    {
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new PersonalName('Joe', 'Smith'),
            Gender::MALE(),
            BuyerStatus::NOT_ACTIVE()
        );

        $order = new Order(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $buyer,
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $this->expectException(InvalidBuyerException::class);
        $this->validator->ensureIsValidOrder($order);
    }

    public function testEnsureIsValidOrderWhenOrderIsInactive(): void
    {
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::NOT_ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new PersonalName('Joe', 'Smith'),
            Gender::MALE(),
            BuyerStatus::ACTIVE()
        );

        $order = new Order(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $buyer,
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $this->expectException(InvalidOrderItemException::class);
        $this->validator->ensureIsValidOrder($order);
    }

    public function testEnsureIsValidOrderWhenOrderHasOtherGender(): void
    {
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::FEMALE()
        );

        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new PersonalName('Joe', 'Smith'),
            Gender::MALE(),
            BuyerStatus::ACTIVE()
        );

        $order = new Order(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $buyer,
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $this->expectException(InvalidOrderItemException::class);
        $this->validator->ensureIsValidOrder($order);
    }

    public function testEnsureIsValidOrderWhenCorrectOrder(): void
    {
        $orderItem = new TicketWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            TicketStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::FEMALE()
        );

        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new PersonalName('Joe', 'Smith'),
            Gender::FEMALE(),
            BuyerStatus::ACTIVE()
        );

        $order = new Order(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $buyer,
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
        $orderItemValidator = $this->prophesize(OrderItemSpecificationValidator::class);
        $orderItemValidator
            ->checkIsValidOrderItem(Argument::type(OrderItem::class))
            ->shouldBeCalledTimes(1);

        $factory = $this->prophesize(OrderItemSpecificationValidatorsFactory::class);
        $factory->createValidators(Argument::type(OrderItem::class), Argument::type(Buyer::class))
            ->willReturn([$orderItemValidator->reveal()])
            ->shouldBeCalled();

        $validator = new OrderValidator($factory->reveal());
        $validator->ensureIsValidOrder($order);
    }
}
