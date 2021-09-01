<?php

namespace App\Tests\Infrastructure\Client\Converter;

use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
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
            Gender::FEMALE(),
            ClientStatus::NOT_ACTIVE(),
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
