<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\Converter;

use App\Domain\Client\Entity\CardNumber;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Entity\ClientStatus;
use App\Domain\Client\Entity\PhoneNumber;
use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\Gender;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;
use App\Infrastructure\Client\Entity\DbClient;
use App\Infrastructure\Shared\Converter\BaseDbConverter;
use App\Infrastructure\Shared\Entity\DbEntity;
use Ramsey\Uuid\Uuid as RamseyUuid;

class ClientDbConverter extends BaseDbConverter
{
    public function convertDomainObjectToDbModel(DomainModel $client): DbClient
    {
        return new DbClient(
            RamseyUuid::fromString((string) $client->getId()),
            $client->getPersonalName()->getFirstName(),
            $client->getPersonalName()->getLastName(),
            (string) $client->getCardNumber(),
            (string) $client->getGender(),
            (string) $client->getClientStatus(),
            $client->getEmailAddress()?->getValue(),
            $client->getPhoneNumber()?->getValue()
        );
    }

    public function convertDbModelToDomainObject(DbEntity $dbClient): Client
    {
        return new Client(
            new Uuid($dbClient->getId()->toString()),
            new PersonalName($dbClient->getFirstName(), $dbClient->getLastName()),
            new CardNumber($dbClient->getCardNumber()),
            new Gender($dbClient->getGender()),
            new ClientStatus($dbClient->getStatus()),
            $dbClient->getEmail() ? new EmailAddress($dbClient->getEmail()) : null,
            $dbClient->getPhoneNumber() ? new PhoneNumber($dbClient->getPhoneNumber()) : null
        );
    }
}
