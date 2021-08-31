<?php

declare(strict_types=1);

namespace App\Domain\Client;

use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Repository\ClientRepository;

class ClientFacade
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {
    }

    /**
     * @throws ClientNotFoundException
     */
    public function getClientByCardNumber(string $cardNumber): Client
    {
        $client = $this->clientRepository->getClientByCardNumber($cardNumber);

        if (null === $client) {
            throw new ClientNotFoundException();
        }

        return $client;
    }
}
