<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\Model\Uuid;
use App\Infrastructure\Client\Converter\ClientDbConverter;
use App\Infrastructure\Client\Entity\DbClient;
use App\Infrastructure\Exception\ClientNotFoundException;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    public function getAll(): ArrayCollection
    {
        $dbClients = $this->getRepository()->findAll();

        return $this->converter->convertAllDbModelToDomainObject($dbClients);
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