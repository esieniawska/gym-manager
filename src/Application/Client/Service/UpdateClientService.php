<?php

declare(strict_types=1);

namespace App\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Dto\UpdateClientDto;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Entity\ClientStatus;
use App\Domain\Client\Entity\PhoneNumber;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\Gender;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;

class UpdateClientService
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ClientDtoAssembler $assembler
    ) {
    }

    public function updateClient(UpdateClientDto $updateClientDto): ClientDto
    {
        $client = $this->clientRepository->getClientById(new Uuid($updateClientDto->getId()));

        if (null === $client) {
            throw new ClientNotFoundException();
        }

        $this->updateFields($updateClientDto, $client);
        $this->clientRepository->updateClient($client);

        return $this->assembler->assembleDomainObjectToDto($client);
    }

    private function updateFields(UpdateClientDto $updateClientDto, Client $client): void
    {
        $client
            ->setPersonalName(new PersonalName($updateClientDto->getFirstName(), $updateClientDto->getLastName()))
            ->setClientStatus(new ClientStatus($updateClientDto->getStatus()))
            ->setGender(new Gender($updateClientDto->getGender()))
            ->setPhoneNumber($updateClientDto->getPhoneNumber() ? new PhoneNumber($updateClientDto->getPhoneNumber()) : null)
            ->setEmailAddress($updateClientDto->getEmail() ? new EmailAddress($updateClientDto->getEmail()) : null);
    }
}
