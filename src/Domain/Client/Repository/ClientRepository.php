<?php

namespace App\Domain\Client\Repository;

use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\Filter;
use App\Domain\Shared\ValueObject\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

interface ClientRepository
{
    public function addClient(Client $client): void;

    public function nextIdentity(): Uuid;

    public function getClientByCardNumber(string $cardNumber): ?Client;

    public function getClientById(Uuid $id): ?Client;

    public function getAll(Filter $filter): ArrayCollection;

    public function updateClient(Client $client): void;
}
