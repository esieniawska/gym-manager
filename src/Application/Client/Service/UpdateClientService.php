<?php

declare(strict_types=1);

namespace App\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Dto\UpdateClientDto;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;

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
            ->updatePersonalName(new PersonalName($updateClientDto->getFirstName(), $updateClientDto->getLastName()))
            ->updateClientStatus(ClientStatus::fromString($updateClientDto->getStatus()))
            ->updateGender(Gender::fromString($updateClientDto->getGender()))
            ->updatePhoneNumber($updateClientDto->getPhoneNumber() ? new PhoneNumber($updateClientDto->getPhoneNumber()) : null)
            ->updateEmailAddress($updateClientDto->getEmail() ? new EmailAddress($updateClientDto->getEmail()) : null);
    }
}
