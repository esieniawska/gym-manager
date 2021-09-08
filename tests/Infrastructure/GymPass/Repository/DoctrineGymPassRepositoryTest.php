<?php

namespace App\Tests\Infrastructure\GymPass\Repository;

use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\GymPass\Converter\DbGymPassConverter;
use App\Infrastructure\GymPass\Entity\DbGymPassWithNumberOfEntries;
use App\Infrastructure\GymPass\Repository\DoctrineGymPassRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    public function testGetGymPass(): void
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
}
