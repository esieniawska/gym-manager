<?php

declare(strict_types=1);

namespace App\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Dto\Filter;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Model\Filter as DomainFilter;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\ValueObject\Uuid;
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

    public function getAllClients(Filter $filter): ArrayCollection
    {
        $clients = $this->clientRepository->getAll(
            new DomainFilter(
                $filter->getCardNumber(),
                $filter->getFirstName(),
                $filter->getLastName(),
                $filter->getStatus()
            )
        );

        return $this->clientDtoAssembler->assembleAll($clients);
    }
}
