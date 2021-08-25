<?php

namespace App\Domain\Client\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Shared\Model\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

interface ClientRepository
{
    public function addClient(Client $client): void;

    public function nextIdentity(): Uuid;

    public function getClientByCardNumber(string $cardNumber): ?Client;

    public function getClientById(Uuid $id): ?Client;

    public function getAll(): ArrayCollection;
}
