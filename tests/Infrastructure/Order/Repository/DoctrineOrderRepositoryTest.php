<?php

namespace App\Tests\Infrastructure\Order\Repository;

use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Offer\Converter\DbOfferConverter;
use App\Infrastructure\Order\Converter\DbOrderConverter;
use App\Infrastructure\Order\Entity\DbOrder;
use App\Infrastructure\Order\Enum\OrderTypeEnum;
use App\Infrastructure\Order\Repository\DoctrineOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DoctrineOrderRepositoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|DbOfferConverter $converterMock;
    private ObjectProphecy|EntityManagerInterface $entityManagerMock;
    private DoctrineOrderRepository $repository;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->prophesize(EntityManagerInterface::class);
        $classMetadataMock = $this->prophesize(ClassMetadata::class);
        $this->entityManagerMock->getClassMetadata(Argument::type('string'))->willReturn($classMetadataMock->reveal());
        $managerRegistryMock = $this->prophesize(ManagerRegistry::class);

        $managerRegistryMock
            ->getManagerForClass(Argument::type('string'))
            ->willReturn($this->entityManagerMock->reveal())
            ->shouldBeCalled();

        $this->converterMock = $this->prophesize(DbOrderConverter::class);
        $this->repository = new DoctrineOrderRepository(
            $managerRegistryMock->reveal(),
            $this->converterMock->reveal()
        );
    }

    public function testAddOrder(): void
    {
        $orderItem = new TicketWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            new NumberOfDays(3)
        );

        $order = new Order(
            new Uuid('300bff1c-171d-4065-bf76-97ca98574667'),
            new Buyer(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $this->converterMock->convertDomainObjectToDbModel($order)
            ->willReturn(
                new DbOrder(
                    RamseyUuid::fromString('300bff1c-171d-4065-bf76-97ca98574667'),
                    RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                    'caabacb3554c96008ba346a61d1839fa',
                    OrderTypeEnum::TYPE_NUMBER_OF_DAYS(),
                    3.33,
                    3,
                    new \DateTimeImmutable(),
                    new \DateTimeImmutable()
                )
            );
        $this->entityManagerMock->persist(Argument::type(DbOrder::class))->shouldBeCalled();
        $this->entityManagerMock->flush()->shouldBeCalled();
        $this->repository->addOrder($order);
    }
}
