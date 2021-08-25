<?php

declare(strict_types=1);

namespace App\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Dto\CreateClientDto;
use App\Domain\Client\Entity\CardNumber;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Entity\ClientStatus;
use App\Domain\Client\Entity\PhoneNumber;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\Gender;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;

class CreateClientService
{
    public function __construct(
        private ClientRepository $repository,
        private CardNumberGenerator $cardNumberGenerator,
        private ClientDtoAssembler $assembler
    ) {
    }

    public function createClient(CreateClientDto $dto): ClientDto
    {
        $client = new Client(
            $this->getClientId(),
            new PersonalName($dto->getFirstName(), $dto->getLastName()),
            new CardNumber($this->getCarNumber()),
            new Gender($dto->getGender()),
            new ClientStatus(ClientStatus::ACTIVE),
            $dto->getEmail() ? new EmailAddress($dto->getEmail()) : null,
            $dto->getPhoneNumber() ? new PhoneNumber($dto->getPhoneNumber()) : null
        );

        $this->repository->addClient($client);

        return $this->assembler->assembleDomainObjectToDto($client);
    }

    private function getClientId(): Uuid
    {
        return $this->repository->nextIdentity();
    }

    private function getCarNumber(): string
    {
        return $this->cardNumberGenerator->generateNumber();
    }
}
