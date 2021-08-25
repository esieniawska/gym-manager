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
        $httpClient = new ClientDto(
            $client->getFirstName(),
            $client->getLastName(),
            $client->getGender(),
            $client->getPhoneNumber(),
            $client->getEmail(),
        );

        return $httpClient
            ->setCardNumber($client->getCardNumber())
            ->setId($client->getId())
            ->setStatus($client->getStatus());
    }
}
