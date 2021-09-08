<?php

declare(strict_types=1);

namespace App\Application\Order\Service;

use App\Application\Client\ClientFacade;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Exception\ClientCanNotCreateOrderException;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Offer\Exception\OfferCanNotBeOrderedException;
use App\Application\Offer\Exception\OfferNotFoundException;
use App\Application\Offer\OfferFacade;
use App\Application\Order\Dto\CreateOrderDto;
use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Factory\OrderItemFactory;
use App\Application\Order\Validator\OrderValidator;
use App\Domain\Order\Event\DomainEventPublisher;
use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\CardNumber;

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
     * @throws ClientCanNotCreateOrderException
     * @throws ClientNotFoundException
     * @throws InvalidValueException
     * @throws OfferCanNotBeOrderedException
     * @throws OfferNotFoundException
     * @throws OrderFailedException
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

    /**
     * @throws ClientNotFoundException
     * @throws ClientCanNotCreateOrderException
     */
    private function getClientThatCanOrder(string $cardNumber): ClientDto
    {
        return $this->clientFacade->getClientByCardNumberThatCanOrder($cardNumber);
    }

    /**
     * @throws OfferCanNotBeOrderedException
     * @throws OfferNotFoundException
     */
    private function getOfferThatCanBeOrdered(string $offerId): OfferDto
    {
        return $this->offerFacade->getOfferByIdThatCanBeOrdered($offerId);
    }

    private function createBuyer(ClientDto $client): Buyer
    {
        return new Buyer(new CardNumber($client->getCardNumber()));
    }
}
