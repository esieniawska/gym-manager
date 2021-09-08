<?php

declare(strict_types=1);

namespace App\Application\Client;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Exception\ClientCanNotCreateOrderException;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Repository\ClientRepository;

class ClientFacade
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ClientDtoAssembler $clientDtoAssembler
    ) {
    }

    /**
     * @throws ClientCanNotCreateOrderException
     * @throws ClientNotFoundException
     */
    public function getClientByCardNumberThatCanOrder(string $cardNumber): ClientDto
    {
        $client = $this->clientRepository->getClientByCardNumber($cardNumber);

        if (null === $client) {
            throw new ClientNotFoundException('Client not found');
        }

        if (!$client->canCreateOrder()) {
            throw new ClientCanNotCreateOrderException('Client can\'t create order.');
        }

        return $this->clientDtoAssembler->assembleDomainObjectToDto($client);
    }
}
