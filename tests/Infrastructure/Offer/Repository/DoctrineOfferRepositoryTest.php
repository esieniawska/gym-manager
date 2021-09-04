<?php

namespace App\Tests\Infrastructure\Offer\Repository;

use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Offer\Converter\DbOfferConverter;
use App\Infrastructure\Offer\Entity\DbOffer;
use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Offer\Exception\OfferNotFoundException;
use App\Infrastructure\Offer\Repository\DoctrineOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DoctrineOfferRepositoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|DbOfferConverter $converterMock;
    private ObjectProphecy|EntityManagerInterface $entityManagerMock;
    private DoctrineOfferRepository $repository;

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

        $this->converterMock = $this->prophesize(DbOfferConverter::class);
        $this->repository = new DoctrineOfferRepository(
            $managerRegistryMock->reveal(),
            $this->converterMock->reveal()
        );
    }

    public function testAddOffer(): void
    {
        $offer = new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(3),
            Gender::MALE()
        );

        $this->converterMock->convertDomainObjectToDbModel($offer)
            ->willReturn(
                new DbOffer(
                    RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                    'offer-name',
                    OfferStatus::ACTIVE(),
                    OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(),
                    1.02,
                    3,
                    Gender::MALE(),
                )
            );
        $this->entityManagerMock->persist(Argument::type(DbOffer::class))->shouldBeCalled();
        $this->entityManagerMock->flush()->shouldBeCalled();
        $this->repository->addOffer($offer);
    }

    public function testGetOfferByIdWhenEntityNotFound(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn(null);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());
        $this->converterMock->convertDbModelToDomainObject(Argument::type(DbOffer::class))->shouldNotBeCalled();

        $this->assertEmpty($this->repository->getOfferById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1')));
    }

    public function testGetOfferByIdWhenEntityExist(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $dbOffer = new DbOffer(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'offer-name',
            OfferStatus::ACTIVE(),
            OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(),
            1.02,
            3,
            Gender::MALE(),
        );
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($dbOffer);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $offer = new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer-name'),
            new Money(1.02),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(3),
            Gender::MALE()
        );
        $this->converterMock->convertDbModelToDomainObject(Argument::type(DbOffer::class))->willReturn($offer);

        $result = $this->repository->getOfferById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));
        $this->assertInstanceOf(TicketOfferWithNumberOfEntriesAndGender::class, $result);
    }

    public function testUpdateOfferWhenEntityExist(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $dbOffer = new DbOffer(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'offer-name',
            OfferStatus::ACTIVE(),
            OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(),
            1.02,
            3,
            Gender::MALE(),
        );
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($dbOffer);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $offer = new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('new-offer-name'),
            new Money(50),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(10),
            Gender::MALE()
        );
        $this->entityManagerMock->flush()->shouldBeCalled();
        $this->repository->updateOffer($offer);
    }

    public function testUpdateOfferStatus(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $dbOffer = new DbOffer(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'offer-name',
            OfferStatus::NOT_ACTIVE(),
            OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(),
            1.02,
            3,
            Gender::MALE(),
        );
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($dbOffer);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $offer = new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('new-offer-name'),
            new Money(50),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(10),
            Gender::MALE()
        );
        $this->entityManagerMock->flush()->shouldBeCalled();
        $this->repository->updateOfferStatus($offer);
    }

    public function testTryUpdateOfferWhenEntityNotFound(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn(null);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $offer = new TicketOfferWithNumberOfEntriesAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('new-offer-name'),
            new Money(50),
            OfferStatus::ACTIVE(),
            new NumberOfEntries(10),
            Gender::MALE()
        );
        $this->expectException(OfferNotFoundException::class);
        $this->repository->updateOffer($offer);
    }
}
