<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\Repository;

use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\Filter;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Client\Converter\ClientDbConverter;
use App\Infrastructure\Client\Entity\DbClient;
use App\Infrastructure\Client\Exception\ClientNotFoundException;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineClientRepository extends DoctrineRepository implements ClientRepository
{
    public function __construct(ManagerRegistry $registry, ClientDbConverter $clientConverter)
    {
        parent::__construct($registry, DbClient::class, $clientConverter);
    }

    public function addClient(Client $client): void
    {
        $this->addEntity($client);
    }

    public function getClientByCardNumber(string $cardNumber): ?Client
    {
        $dbClient = $this->getRepository()->findOneBy(['cardNumber' => $cardNumber]);

        return null === $dbClient ? null : $this->converter->convertDbModelToDomainObject($dbClient);
    }

    public function getClientById(Uuid $id): ?Client
    {
        $dbClient = $this->getRepository()->find((string) $id);

        return null === $dbClient ? null : $this->converter->convertDbModelToDomainObject($dbClient);
    }

    public function getAll(Filter $filter): ArrayCollection
    {
        $dbClients = $this->findAllClients($filter);

        return $this->converter->convertAllDbModelToDomainObject($dbClients);
    }

    private function findAllClients(Filter $filter): array
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getRepository()->createQueryBuilder('client');
        $queryBuilder
            ->select('client');

        if (null !== $filter->getFirstName()) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('LOWER(client.firstName)', ':firstName'))
                ->setParameter(':firstName', '%'.strtolower($filter->getFirstName()).'%');
        }

        if (null !== $filter->getLastName()) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('LOWER(client.lastName)', ':lastName'))
                ->setParameter(':lastName', '%'.strtolower($filter->getLastName()).'%');
        }

        if (null !== $filter->getCardNumber()) {
            $queryBuilder
                ->andWhere('client.cardNumber = :cardNumber')
                ->setParameter(':cardNumber', $filter->getCardNumber());
        }

        if (null !== $filter->getStatus()) {
            $queryBuilder
                ->andWhere('client.status = :status')
                ->setParameter(':status', $filter->getStatus());
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function updateClient(Client $client): void
    {
        $dbClient = $this->getRepository()->find((string) $client->getId());

        if (null === $dbClient) {
            throw new ClientNotFoundException();
        }

        $this->updateDbClientFields($client, $dbClient);
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    private function updateDbClientFields(Client $client, DbClient $dbClient): void
    {
        $dbClient
            ->setFirstName($client->getPersonalName()->getFirstName())
            ->setLastName($client->getPersonalName()->getLastName())
            ->setStatus((string) $client->getClientStatus())
            ->setGender((string) $client->getGender())
            ->setPhoneNumber($client->getPhoneNumber()?->getValue())
            ->setEmail($client->getEmailAddress()?->getValue());
    }
}
