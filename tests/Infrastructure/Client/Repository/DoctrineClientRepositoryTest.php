<?php

namespace App\Tests\Infrastructure\Client\Repository;

use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Client\Converter\ClientDbConverter;
use App\Infrastructure\Client\Entity\DbClient;
use App\Infrastructure\Client\Exception\ClientNotFoundException;
use App\Infrastructure\Client\Repository\DoctrineClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DoctrineClientRepositoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ClientDbConverter $converterMock;
    private ObjectProphecy|EntityManagerInterface $entityManagerMock;
    private DoctrineClientRepository $repository;

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

        $this->converterMock = $this->prophesize(ClientDbConverter::class);
        $this->repository = new DoctrineClientRepository(
            $managerRegistryMock->reveal(),
            $this->converterMock->reveal()
        );
    }

    public function testAddClient(): void
    {
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new Gender(Gender::FEMALE),
            new ClientStatus(ClientStatus::NOT_ACTIVE),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );
        $this->converterMock->convertDomainObjectToDbModel($client)
            ->willReturn(
                new DbClient(
                    RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                    'Joe',
                    'Smith',
                    '3da8b78de7732860e770d2a0a17b7b82',
                    Gender::FEMALE,
                    ClientStatus::NOT_ACTIVE,
                    'test@example.com',
                    '123456789'
                )
            );
        $this->entityManagerMock->persist(Argument::type(DbClient::class))->shouldBeCalled();
        $this->entityManagerMock->flush()->shouldBeCalled();
        $this->repository->addClient($client);
    }

    public function testNextIdentity(): void
    {
        $uuid = $this->repository->nextIdentity();
        $this->assertInstanceOf(Uuid::class, $uuid);
    }

    public function testGetClientByCardNumberWhenDbEntityNotFound(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->findOneBy(['cardNumber' => 'test-card-number'])->willReturn(null);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());
        $this->converterMock->convertDbModelToDomainObject(Argument::type(DbClient::class))->shouldNotBeCalled();
        $result = $this->repository->getClientByCardNumber('test-card-number');
        $this->assertEmpty($result);
    }

    public function testGetClientByCardNumberWhenDbEntityExist(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $clientMock = $this->prophesize(DbClient::class);
        $entityRepository->findOneBy(['cardNumber' => 'test-card-number'])->willReturn($clientMock->reveal());
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());
        $domain = $this->prophesize(Client::class);
        $this->converterMock
            ->convertDbModelToDomainObject(Argument::type(DbClient::class))
            ->willReturn($domain->reveal());

        $result = $this->repository->getClientByCardNumber('test-card-number');
        $this->assertInstanceOf(Client::class, $result);
    }

    public function testGetClientByIdWhenClientNotFound(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn(null);
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());
        $this->converterMock->convertDbModelToDomainObject(Argument::type(DbClient::class))->shouldNotBeCalled();

        $this->assertEmpty($this->repository->getClientById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1')));
    }

    public function testGetClientByIdWhenClientExist(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn(
            new DbClient(
                RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
                'Joe',
                'Smith',
                '3da8b78de7732860e770d2a0a17b7b82',
                Gender::FEMALE,
                ClientStatus::NOT_ACTIVE,
                'test@example.com',
                '123456789'
            )
        );
        $this->entityManagerMock->getRepository(Argument::type('string'))->willReturn($entityRepository->reveal());
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new Gender(Gender::FEMALE),
            new ClientStatus(ClientStatus::NOT_ACTIVE),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );
        $this->converterMock->convertDbModelToDomainObject(Argument::type(DbClient::class))
            ->willReturn($client);

        $result = $this->repository->getClientById(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));
        $this->assertEquals($client, $result);
    }

    public function testUpdateClientWhenClientExist(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);

        $dbClient = $this->prophesize(DbClient::class);
        $dbClient
            ->setFirstName('Joe')
            ->willReturn($dbClient)
            ->shouldBeCalled();

        $dbClient
            ->setLastName('Smith')
            ->willReturn($dbClient)
            ->shouldBeCalled();

        $dbClient
            ->setStatus(ClientStatus::ACTIVE)
            ->willReturn($dbClient)
            ->shouldBeCalled();

        $dbClient
            ->setGender(Gender::MALE)
            ->willReturn($dbClient)
            ->shouldBeCalled();

        $dbClient
            ->setPhoneNumber(null)
            ->willReturn($dbClient)
            ->shouldBeCalled();

        $dbClient
            ->setEmail('test123@example.com')
            ->willReturn($dbClient)
            ->shouldBeCalled();

        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn($dbClient->reveal());

        $this->entityManagerMock
            ->getRepository(Argument::type('string'))
            ->willReturn($entityRepository->reveal());
        $this->entityManagerMock->flush()->shouldBeCalled();

        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new Gender(Gender::MALE),
            new ClientStatus(ClientStatus::ACTIVE),
            new EmailAddress('test123@example.com'),
            null
        );

        $this->repository->updateClient($client);
    }

    public function testUpdateClientWhenClientNotFound(): void
    {
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find('7d24cece-b0c6-4657-95d5-31180ebfc8e1')->willReturn(null);

        $this->entityManagerMock
            ->getRepository(Argument::type('string'))
            ->willReturn($entityRepository->reveal());

        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new Gender(Gender::MALE),
            new ClientStatus(ClientStatus::ACTIVE),
            new EmailAddress('test123@example.com'),
            null
        );

        $this->expectException(ClientNotFoundException::class);
        $this->repository->updateClient($client);
    }
}
