<?php

declare(strict_types=1);

namespace App\Application\Client\Service;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Application\Client\Dto\CreateClientDto;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;

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
            Gender::fromString($dto->getGender()),
            ClientStatus::ACTIVE(),
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
