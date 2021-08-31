<?php

declare(strict_types=1);

namespace App\Application\Order\Service;

use App\Application\Order\DataTransformer\BuyerDataTransformer;
use App\Application\Order\Dto\CreateOrderDto;
use App\Application\Order\Factory\OrderItemFactory;
use App\Domain\Order\Event\DomainEventPublisher;
use App\Domain\Order\Exception\InvalidBuyerException;
use App\Domain\Order\Exception\InvalidOrderItemException;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Order\Validator\OrderValidator;
use App\Domain\Shared\Exception\InvalidValueException;

class CreateOrderService
{
    public function __construct(
        private BuyerDataTransformer $buyerDataTransformer,
        private OrderItemFactory $orderItemFactory,
        private OrderValidator $orderValidator,
        private OrderRepository $orderRepository,
        private DomainEventPublisher $eventPublisher
    ) {
    }

    /**
     * @throws InvalidOrderItemException
     * @throws InvalidBuyerException
     * @throws InvalidValueException
     */
    public function create(CreateOrderDto $orderDto): void
    {
        $buyer = $this->buyerDataTransformer->createBuyerFromClientCardNumber($orderDto->getCardNumber());
        $orderItem = $this->orderItemFactory->createOrderItemFromOfferId($orderDto->getOfferId());

        $order = new Order(
            $this->orderRepository->nextIdentity(),
            $buyer,
            $orderItem,
            $orderDto->getStartDate(),
            new \DateTimeImmutable()
        );

        $this->orderValidator->ensureIsValidOrder($order);

        $this->orderRepository->addOrder($order);
        $this->eventPublisher->publishOrderCreatedEvent($order);
    }
}
