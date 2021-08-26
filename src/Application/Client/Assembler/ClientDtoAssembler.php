<?php

declare(strict_types=1);

namespace App\Application\Client\Assembler;

use App\Application\Client\Dto\ClientDto;
use App\Application\Shared\Assembler\BaseDtoAssembler;
use App\Domain\Shared\Model\DomainModel;

class ClientDtoAssembler extends BaseDtoAssembler
{
    public function assembleDomainObjectToDto(DomainModel $client): ClientDto
    {
        return new ClientDto(
            (string) $client->getId(),
            (string) $client->getCardNumber(),
            (string) $client->getClientStatus(),
            $client->getPersonalName()->getFirstName(),
            $client->getPersonalName()->getLastName(),
            (string) $client->getGender(),
            $client->getPhoneNumber()?->getValue(),
            $client->getEmailAddress()?->getValue()
        );
    }
}
