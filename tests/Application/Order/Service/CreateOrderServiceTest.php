<?php

namespace App\Tests\Application\Order\Service;

use App\Application\Order\Dto\CreateOrderDto;
use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Factory\OrderItemFactory;
use App\Application\Order\Service\CreateOrderService;
use App\Application\Order\Validator\OrderValidator;
use App\Domain\Client\ClientFacade;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Offer\OfferFacade;
use App\Domain\Order\Event\DomainEventPublisher;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateOrderServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OrderItemFactory $orderItemFactoryMock;
    private ObjectProphecy|OrderValidator $orderValidatorMock;
    private ObjectProphecy|OrderRepository $orderRepositoryMock;
    private ObjectProphecy|DomainEventPublisher $eventPublisherMock;
    private ObjectProphecy|ClientFacade $clientFacadeMock;
    private ObjectProphecy|OfferFacade $offerFacadeMock;
    private CreateOrderService $service;

    protected function setUp(): void
    {
        $this->orderItemFactoryMock = $this->prophesize(OrderItemFactory::class);
        $this->orderValidatorMock = $this->prophesize(OrderValidator::class);
        $this->orderRepositoryMock = $this->prophesize(OrderRepository::class);
        $this->eventPublisherMock = $this->prophesize(DomainEventPublisher::class);
        $this->clientFacadeMock = $this->prophesize(ClientFacade::class);
        $this->offerFacadeMock = $this->prophesize(OfferFacade::class);

        $this->service = new CreateOrderService(
            $this->orderItemFactoryMock->reveal(),
            $this->orderValidatorMock->reveal(),
            $this->orderRepositoryMock->reveal(),
            $this->eventPublisherMock->reveal(),
            $this->clientFacadeMock->reveal(),
            $this->offerFacadeMock->reveal()
        );
    }

    public function testCreateOrderWhenClientCantCreateOrder(): void
    {
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            Gender::FEMALE(),
            ClientStatus::NOT_ACTIVE(),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );

        $this->clientFacadeMock
            ->getClientByCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->willReturn($client);

        $this->expectException(OrderFailedException::class);

        $dto = new CreateOrderDto(
            '3da8b78de7732860e770d2a0a17b7b82',
            '12asxvd23b0c6-4657-95d5-31180ebfc8e1',
            new \DateTimeImmutable()
        );
        $this->service->create($dto);
    }

    public function testCreateOrderWhenOfferCantBeOrdered(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer'),
            new Money(3.33),
            OfferStatus::NOT_ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            Gender::FEMALE(),
            ClientStatus::ACTIVE(),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );

        $this->clientFacadeMock
            ->getClientByCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->willReturn($client);

        $this->offerFacadeMock
            ->getOfferById('12asxvd23b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn($offer);

        $this->expectException(OrderFailedException::class);

        $dto = new CreateOrderDto(
            '3da8b78de7732860e770d2a0a17b7b82',
            '12asxvd23b0c6-4657-95d5-31180ebfc8e1',
            new \DateTimeImmutable()
        );
        $this->service->create($dto);
    }

    public function testSuccessfulCreateOrder(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer'),
            new Money(3.33),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            Gender::FEMALE(),
            ClientStatus::ACTIVE(),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );

        $orderItem = new TicketWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            new NumberOfDays(3)
        );
        $this->clientFacadeMock
            ->getClientByCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->willReturn($client);

        $this->offerFacadeMock
            ->getOfferById('12asxvd23b0c6-4657-95d5-31180ebfc8e1')
            ->willReturn($offer);

        $this->orderValidatorMock
            ->ensureIsClientCanBuyThisOffer($client, $offer)
            ->shouldBeCalled();

        $this->orderItemFactoryMock
            ->createOrderItem($offer)
            ->willReturn($orderItem);

        $this->orderRepositoryMock
            ->nextIdentity()
            ->willReturn(new Uuid('3eda35dc-b0c6-4657-95d5-31180ebfc8e1'));

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
