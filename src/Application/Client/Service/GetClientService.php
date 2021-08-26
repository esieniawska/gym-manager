<?php

declare(strict_types=1);

namespace App\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\Model\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

class GetClientService
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ClientDtoAssembler $clientDtoAssembler
    ) {
    }

    /**
     * @throws ClientNotFoundException
     */
    public function getClientById(string $id): ClientDto
    {
        $client = $this->clientRepository->getClientById(new Uuid($id));

        if (null === $client) {
            throw new ClientNotFoundException();
        }

        return $this->clientDtoAssembler->assembleDomainObjectToDto($client);
    }

    public function getAllClients(): ArrayCollection
    {
        $clients = $this->clientRepository->getAll();

        return $this->clientDtoAssembler->assembleAll($clients);
    }
}
