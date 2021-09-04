<?php

declare(strict_types=1);

namespace App\Application\Order\Service;

use App\Application\Order\Dto\CreateOrderDto;
use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Factory\OrderItemFactory;
use App\Application\Order\Validator\OrderValidator;
use App\Domain\Client\ClientFacade;
use App\Domain\Client\Model\Client;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\OfferFacade;
use App\Domain\Order\Event\DomainEventPublisher;
use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Shared\Exception\InvalidValueException;

class CreateOrderService
{
    public function __construct(
        private OrderItemFactory $orderItemFactory,
        private OrderValidator $orderValidator,
        private OrderRepository $orderRepository,
        private DomainEventPublisher $eventPublisher,
        private ClientFacade $clientFacade,
        private OfferFacade $offerFacade
    ) {
    }

    /**
     * @throws OrderFailedException
     * @throws InvalidValueException
     */
    public function create(CreateOrderDto $orderDto): void
    {
        $client = $this->getClientThatCanOrder($orderDto->getCardNumber());
        $offerTicket = $this->getOfferThatCanBeOrdered($orderDto->getOfferId());
        $this->orderValidator->ensureIsClientCanBuyThisOffer($client, $offerTicket);

        $buyer = $this->createBuyer($client);
        $orderItem = $this->orderItemFactory->createOrderItem($offerTicket);

        $order = new Order(
            $this->orderRepository->nextIdentity(),
            $buyer,
            $orderItem,
            $orderDto->getStartDate(),
            new \DateTimeImmutable()
        );

        $this->orderRepository->addOrder($order);
        $this->eventPublisher->publishOrderCreatedEvent($order);
    }

    private function getClientThatCanOrder(string $cardNumber): Client
    {
        $client = $this->clientFacade->getClientByCardNumber($cardNumber);

        if (!$client->canCreateOrder()) {
            throw new OrderFailedException('Client can\'t create order.');
        }

        return $client;
    }

    private function getOfferThatCanBeOrdered(string $offerId): OfferTicket
    {
        $offerTicket = $this->offerFacade->getOfferById($offerId);

        if (!$offerTicket->canBeOrdered()) {
            throw new OrderFailedException('Offer can\'t be ordered.');
        }

        return $offerTicket;
    }

    private function createBuyer(Client $client): Buyer
    {
        return new Buyer($client->getCardNumber());
    }
}
