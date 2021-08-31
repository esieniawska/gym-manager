<?php

namespace App\Tests\Application\Order\Service;

use App\Application\Order\DataTransformer\BuyerDataTransformer;
use App\Application\Order\Dto\CreateOrderDto;
use App\Application\Order\Factory\OrderItemFactory;
use App\Application\Order\Service\CreateOrderService;
use App\Domain\Order\Event\DomainEventPublisher;
use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\BuyerStatus;
use App\Domain\Order\Model\NumberOfDays;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\TicketStatus;
use App\Domain\Order\Model\TicketWithNumberOfDaysAndGender;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Order\Validator\OrderValidator;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateOrderServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|BuyerDataTransformer $buyerDataTransformerMock;
    private ObjectProphecy|OrderItemFactory $orderItemFactoryMock;
    private ObjectProphecy|OrderValidator $orderValidatorMock;
    private ObjectProphecy|OrderRepository $orderRepositoryMock;
    private ObjectProphecy|DomainEventPublisher $eventPublisherMock;
    private CreateOrderService $service;

    protected function setUp(): void
    {
        $this->buyerDataTransformerMock = $this->prophesize(BuyerDataTransformer::class);
        $this->orderItemFactoryMock = $this->prophesize(OrderItemFactory::class);
        $this->orderValidatorMock = $this->prophesize(OrderValidator::class);
        $this->orderRepositoryMock = $this->prophesize(OrderRepository::class);
        $this->eventPublisherMock = $this->prophesize(DomainEventPublisher::class);

        $this->service = new CreateOrderService(
            $this->buyerDataTransformerMock->reveal(),
            $this->orderItemFactoryMock->reveal(),
            $this->orderValidatorMock->reveal(),
            $this->orderRepositoryMock->reveal(),
            $this->eventPublisherMock->reveal()
        );
    }

    public function testCreateOrder(): void
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

        $this->buyerDataTransformerMock
            ->createBuyerFromClientCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->willReturn($buyer);

        $this->orderItemFactoryMock
            ->createOrderItemFromOfferId('12asxvd23b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn($orderItem);

        $this->orderRepositoryMock
            ->nextIdentity()
            ->willReturn(new Uuid('3eda35dc-b0c6-4657-95d5-31180ebfc8e1'));

        $this->orderValidatorMock
            ->ensureIsValidOrder(Argument::type(Order::class))
            ->shouldBeCalled();

        $this->orderRepositoryMock
            ->addOrder(Argument::type(Order::class))
            ->shouldBeCalled();

        $this->eventPublisherMock
            ->publishOrderCreatedEvent(Argument::type(Order::class))
            ->shouldBeCalled();

        $dto = new CreateOrderDto(
            '3da8b78de7732860e770d2a0a17b7b82',
            '12asxvd23b0c6-4657-95d5-31180ebfc8e1',
            new \DateTimeImmutable()
        );
        $this->service->create($dto);
    }
}
