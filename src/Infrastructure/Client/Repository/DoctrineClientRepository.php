<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepository;
use App\Infrastructure\Client\Converter\ClientDbConverter;
use App\Infrastructure\Client\Entity\DbClient;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
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
}
