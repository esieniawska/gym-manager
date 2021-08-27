<?php

namespace App\Tests\Infrastructure\User\Repository;

use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Model\PasswordHash;
use App\Domain\User\Model\Roles;
use App\Domain\User\Model\User;
use App\Infrastructure\User\Converter\UserDbConverter;
use App\Infrastructure\User\Entity\DbUser;
use App\Infrastructure\User\Repository\DoctrineUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DoctrineUserRepositoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|UserDbConverter $converterMock;
    private ObjectProphecy|EntityManagerInterface $entityManagerMock;
    private DoctrineUserRepository $repository;

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

        $this->converterMock = $this->prophesize(UserDbConverter::class);
        $this->repository = new DoctrineUserRepository(
            $managerRegistryMock->reveal(),
            $this->converterMock->reveal()
        );
    }

    public function testAddUser(): void
    {
        $this->entityManagerMock->persist(Argument::type(DbUser::class))->shouldBeCalled();
        $this->entityManagerMock->flush()->shouldBeCalled();

        $user = new User(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new EmailAddress('test@example.com'),
            new PasswordHash('hash'),
            new Roles([Roles::ROLE_USER])
        );

        $this->converterMock
            ->convertDomainObjectToDbModel($user)
            ->willReturn(
                new DbUser(
                    RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                    'test@example.com',
                    'hash',
                    'Joe',
                    'Smith',
                    [Roles::ROLE_USER]
                )
            );

        $this->repository->addUser($user);
    }

    public function testGetByEmailWhenUserExist(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository
            ->findOneBy(['email' => 'test@example.com'])
            ->willReturn(
                new DbUser(
                    RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                    'test@example.com',
                    'hash',
                    'Joe',
                    'Smith',
                    [Roles::ROLE_USER]
                )
            );
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $user = new User(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new EmailAddress('test@example.com'),
            new PasswordHash('hash'),
            new Roles([Roles::ROLE_USER])
        );

        $this->converterMock->convertDbModelToDomainObject(Argument::type(DbUser::class))->willReturn($user);

        $this->assertEquals($user, $this->repository->getByEmail('test@example.com'));
    }

    public function testGetByEmailWhenUserNotFound(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository
            ->findOneBy(['email' => 'test@example.com'])
            ->willReturn(null);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $this->converterMock->convertDbModelToDomainObject(Argument::type(DbUser::class))->shouldNotBeCalled();

        $this->assertEmpty($this->repository->getByEmail('test@example.com'));
    }
}
