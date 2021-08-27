<?php

namespace App\Tests\UI\Client\Converter;

use App\Application\Client\Dto\ClientDto;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Shared\ValueObject\Gender;
use App\UI\Client\Converter\ClientDtoCollectionConverter;
use App\UI\Client\Http\Dto\ClientDto as HttpClientDto;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ClientDtoConverterTest extends TestCase
{
    public function testCreateHttpFromApplicationDto(): void
    {
        $clientDto = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@example.com'
        );

        $httpDto = (new HttpClientDto())
            ->setStatus(ClientStatus::ACTIVE)
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->setFirstName('Joe')
            ->setLastName('Smith')
            ->setGender(Gender::MALE)
            ->setPhoneNumber('123456789')
            ->setEmail('test@example.com');

        $converter = new ClientDtoCollectionConverter();
        $result = $converter->createHttpFromApplicationDto($clientDto);
        $this->assertEquals($httpDto, $result);
    }

    public function testCreateHttpFromApplicationDtoCollection(): void
    {
        $clientDto = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@example.com'
        );

        $httpDto = (new HttpClientDto())
            ->setStatus(ClientStatus::ACTIVE)
            ->setId('7d24cece-b0c6-4657-95d5-31180ebfc8e1')
            ->setCardNumber('3da8b78de7732860e770d2a0a17b7b82')
            ->setFirstName('Joe')
            ->setLastName('Smith')
            ->setGender(Gender::MALE)
            ->setPhoneNumber('123456789')
            ->setEmail('test@example.com');

        $converter = new ClientDtoCollectionConverter();
        $result = $converter->createHttpFromApplicationDtoCollection(new ArrayCollection([$clientDto]));
        $this->assertEquals(1, $result->count());
        $this->assertEquals($httpDto, $result->first());
    }
}
