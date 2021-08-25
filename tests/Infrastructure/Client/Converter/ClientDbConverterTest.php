<?php

namespace App\Tests\Infrastructure\Client\Converter;

use App\Domain\Client\Entity\CardNumber;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Entity\ClientStatus;
use App\Domain\Client\Entity\PhoneNumber;
use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\Gender;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;
use App\Infrastructure\Client\Converter\ClientDbConverter;
use App\Infrastructure\Client\Entity\DbClient;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class ClientDbConverterTest extends TestCase
{
    public function testConvertDomainObjectToDbModel(): void
    {
        $client = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new Gender(Gender::FEMALE),
            new ClientStatus(ClientStatus::NOT_ACTIVE),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );
        $converter = new ClientDbConverter();
        $result = $converter->convertDomainObjectToDbModel($client);
        $this->assertInstanceOf(DbClient::class, $result);
    }

    public function testConvertDbModelToDomainObject(): void
    {
        $dbClient = new DbClient(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'Joe',
            'Smith',
            '3da8b78de7732860e770d2a0a17b7b82',
            Gender::FEMALE,
            ClientStatus::NOT_ACTIVE,
            'test@example.com',
            null
        );
        $converter = new ClientDbConverter();
        $result = $converter->convertDbModelToDomainObject($dbClient);
        $this->assertInstanceOf(Client::class, $result);
        $this->assertEmpty($result->getPhoneNumber());
    }

    public function testConvertAllDbModelToDomainObject(): void
    {
        $dbClient = new DbClient(
            RamseyUuid::fromString('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            'Joe',
            'Smith',
            '3da8b78de7732860e770d2a0a17b7b82',
            Gender::FEMALE,
            ClientStatus::NOT_ACTIVE,
            'test@example.com',
            null
        );
        $converter = new ClientDbConverter();
        $result = $converter->convertAllDbModelToDomainObject([$dbClient]);
        $this->assertInstanceOf(ArrayCollection::class, $result);
        $this->assertInstanceOf(Client::class, $result->first());
    }
}
