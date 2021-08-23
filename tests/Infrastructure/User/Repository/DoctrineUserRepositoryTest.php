<?php

namespace App\Tests\Infrastructure\User\Repository;

use App\Domain\Shared\Model\StringValueObject;
use App\Domain\User\Entity\EmailAddress;
use App\Domain\User\Entity\Enum\UserRole;
use App\Domain\User\Entity\PasswordHash;
use App\Domain\User\Entity\Roles;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Converter\UserConverter;
use App\Infrastructure\User\Entity\DbUser;
use App\Infrastructure\User\Repository\DoctrineUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DoctrineUserRepositoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|UserConverter $converterMock;
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

        $this->converterMock = $this->prophesize(UserConverter::class);
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
            new StringValueObject('Joe'),
            new StringValueObject('Smith'),
            new EmailAddress('test@example.com'),
            new PasswordHash('hash'),
            new Roles([UserRole::ROLE_USER])
        );

        $this->converterMock
            ->convertDomainObjectToDbModel($user)
            ->willReturn(new DbUser('test@example.com', 'hash', 'Joe', 'Smith', [UserRole::ROLE_USER]));

        $this->repository->addUser($user);
    }

    public function testGetByEmailWhenUserExist(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository
            ->findOneBy(['email' => 'test@example.com'])
            ->willReturn(new DbUser('test@example.com', 'hash', 'Joe', 'Smith', [UserRole::ROLE_USER]));
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());

        $user = new User(
            new StringValueObject('Joe'),
            new StringValueObject('Smith'),
            new EmailAddress('test@example.com'),
            new PasswordHash('hash'),
            new Roles([UserRole::ROLE_USER])
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
