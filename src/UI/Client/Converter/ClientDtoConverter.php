<?php

declare(strict_types=1);

namespace App\UI\Client\Converter;

use App\Application\Shared\Dto\BaseDto as ApplicationDto;
use App\UI\Client\Http\Dto\ClientDto;
use App\UI\Shared\Converter\BaseDtoConverter;

class ClientDtoConverter extends BaseDtoConverter
{
    public function createHttpFromApplicationDto(ApplicationDto $client): ClientDto
    {
        return (new ClientDto())
            ->setCardNumber($client->getCardNumber())
            ->setId($client->getId())
            ->setStatus($client->getStatus())
            ->setFirstName($client->getFirstName())
            ->setLastName($client->getLastName())
            ->setGender($client->getGender())
            ->setPhoneNumber($client->getPhoneNumber())
            ->setEmail($client->getEmail());
    }
}
