<?php

namespace App\Tests\Infrastructure\GymPass\Repository;

use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymEntering;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Client\Entity\DbClient;
use App\Infrastructure\GymPass\Converter\DbGymPassConverter;
use App\Infrastructure\GymPass\Entity\DbGymEntering;
use App\Infrastructure\GymPass\Entity\DbGymPassWithEndDate;
use App\Infrastructure\GymPass\Entity\DbGymPassWithNumberOfEntries;
use App\Infrastructure\GymPass\Exception\GymPassNotFoundException;
use App\Infrastructure\GymPass\Exception\InvalidGymPassTypeException;
use App\Infrastructure\GymPass\Repository\DoctrineGymPassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DoctrineGymPassRepositoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|DbGymPassConverter $converterMock;
    private ObjectProphecy|EntityManagerInterface $entityManagerMock;
    private DoctrineGymPassRepository $repository;

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

        $this->converterMock = $this->prophesize(DbGymPassConverter::class);
        $this->repository = new DoctrineGymPassRepository(
            $managerRegistryMock->reveal(),
            $this->converterMock->reveal()
        );
    }

    public function testAddGymPass(): void
    {
        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Client(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            new \DateTimeImmutable(),
            new NumberOfEntries(3)
        );

        $this->converterMock->convertDomainObjectToDbModel($gymPass)
            ->willReturn(
                new DbGymPassWithNumberOfEntries(
                    RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                    'caabacb3554c96008ba346a61d1839fa',
                    new \DateTimeImmutable(),
                    3
                )
            );
        $this->entityManagerMock->persist(Argument::type(DbGymPassWithNumberOfEntries::class))->shouldBeCalled();
        $this->entityManagerMock->flush()->shouldBeCalled();
        $this->repository->addGymPass($gymPass);
    }

    public function testGetGymPassByIdWhenDbEntityExist(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $dbGymPass = new DbGymPassWithNumberOfEntries(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'caabacb3554c96008ba346a61d1839fa',
            new \DateTimeImmutable(),
            4
        );
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($dbGymPass);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $domainGymPass = new GymPassWithNumberOfEntries(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Client(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            new \DateTimeImmutable(),
            new NumberOfEntries(4)
        );
        $this->converterMock
            ->convertDbModelToDomainObject($dbGymPass)
            ->willReturn($domainGymPass);

        $result = $this->repository->getGymPass(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));
        $this->assertInstanceOf(GymPassWithNumberOfEntries::class, $result);
    }

    public function testGetGymPassByIdWhenGymPassNotFound(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn(null);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());
        $this->converterMock->convertDbModelToDomainObject(Argument::type(DbClient::class))->shouldNotBeCalled();

        $this->assertEmpty($this->repository->getGymPass(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1')));
    }

    public function testAddLastGymPassEntering(): void
    {
        $existDate = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $newDate = new \DateTimeImmutable();
        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Client(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            new \DateTimeImmutable(),
            new NumberOfEntries(3),
            [
                new GymEntering($existDate),
                new GymEntering($newDate),
            ]
        );

        $dbModel = new DbGymPassWithNumberOfEntries(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'caabacb3554c96008ba346a61d1839fa',
            new \DateTimeImmutable(),
            3
        );

        $dbModel->addGymEntering(new DbGymEntering(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            $dbModel,
            $existDate
        ));

        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($dbModel);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $this->entityManagerMock->flush()->shouldBeCalled();
        $this->repository->addLastGymPassEntering($gymPass);
        $this->assertCount(2, $dbModel->getGymEnteringList());
        $this->assertEquals($newDate, $dbModel->getGymEnteringList()[1]->getDate());
    }

    public function testSuccessfulUpdateGymPassDates(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $oldEndDate = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $newEndDate = $oldEndDate->add(new \DateInterval('P4D'));
        $startLockDate = new \DateTimeImmutable();
        $endLockDate = (new \DateTimeImmutable())->add(new \DateInterval('P4D'));

        $gymPass = new GymPassWithEndDate(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Client(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            $startDate,
            $newEndDate,
            $startLockDate,
            $endLockDate
        );

        $dbModel = new DbGymPassWithEndDate(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'caabacb3554c96008ba346a61d1839fa',
            $startDate,
            $oldEndDate,
        );

        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($dbModel);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $this->entityManagerMock->flush()->shouldBeCalled();
        $this->repository->updateGymPassDates($gymPass);
        $this->assertEquals($startLockDate, $dbModel->getLockStartDate());
        $this->assertEquals($endLockDate, $dbModel->getLockEndDate());
        $this->assertEquals($newEndDate, $dbModel->getEndDate());
        $this->assertEquals($startDate, $dbModel->getStartDate());
    }

    public function testFailedUpdateGymPassDatesWhenGymPassNotFound(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $oldEndDate = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $newEndDate = $oldEndDate->add(new \DateInterval('P4D'));
        $startLockDate = new \DateTimeImmutable();
        $endLockDate = (new \DateTimeImmutable())->add(new \DateInterval('P4D'));

        $gymPass = new GymPassWithEndDate(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Client(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            $startDate,
            $newEndDate,
            $startLockDate,
            $endLockDate
        );

        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn(null);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $this->expectException(GymPassNotFoundException::class);
        $this->repository->updateGymPassDates($gymPass);
    }

    public function testFailedUpdateGymPassDatesWhenInvalidType(): void
    {
        $startDate = (new \DateTimeImmutable())->modify('-1 day');
        $oldEndDate = (new \DateTimeImmutable())->add(new \DateInterval('P2D'));
        $newEndDate = $oldEndDate->add(new \DateInterval('P4D'));
        $startLockDate = new \DateTimeImmutable();
        $endLockDate = (new \DateTimeImmutable())->add(new \DateInterval('P4D'));

        $gymPass = new GymPassWithEndDate(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Client(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            $startDate,
            $newEndDate,
            $startLockDate,
            $endLockDate
        );

        $dbModel = new DbGymPassWithNumberOfEntries(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'caabacb3554c96008ba346a61d1839fa',
            $startDate,
            4
        );

        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($dbModel);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $this->expectException(InvalidGymPassTypeException::class);
        $this->repository->updateGymPassDates($gymPass);
    }
}
