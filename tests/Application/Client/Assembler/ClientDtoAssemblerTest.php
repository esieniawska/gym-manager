<?php

namespace App\Tests\Application\Client\Assembler;

use App\Application\Client\Assembler\ClientDtoAssembler;
use App\Application\Client\Dto\ClientDto;
use App\Domain\Client\Model\Client;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Client\Model\PhoneNumber;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ClientDtoAssemblerTest extends TestCase
{
    public function testAssembleDomainObjectToDto(): void
    {
        $domainModel = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new Gender(Gender::FEMALE),
            new ClientStatus(ClientStatus::NOT_ACTIVE),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );

        $assembler = new ClientDtoAssembler();
        $result = $assembler->assembleDomainObjectToDto($domainModel);
        $this->assertInstanceOf(ClientDto::class, $result);
    }

    public function testAssembleAll(): void
    {
        $domainModel = new Client(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new Gender(Gender::FEMALE),
            new ClientStatus(ClientStatus::NOT_ACTIVE),
            new EmailAddress('test@example.com'),
            new PhoneNumber('123456789')
        );

        $assembler = new ClientDtoAssembler();
        $result = $assembler->assembleAll(new ArrayCollection([$domainModel, $domainModel]));
        $this->assertInstanceOf(ArrayCollection::class, $result);
        $this->assertEquals(2, $result->count());
        $this->assertInstanceOf(ClientDto::class, $result->first());
    }
}
