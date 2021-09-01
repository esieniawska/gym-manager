<?php

namespace App\Tests\Domain\GymPass\Model;

use App\Domain\GymPass\Model\Client;
use App\Domain\Shared\ValueObject\CardNumber;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testIsTheSameClientWhenCardNumberIsTheSame(): void
    {
        $client = new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82'));
        $this->assertTrue($client->isTheSameClient(new CardNumber('3da8b78de7732860e770d2a0a17b7b82')));
    }

    public function testIsTheSameClientWhenCardNumberIsOther(): void
    {
        $client = new Client(new CardNumber('3da8b78de7732860e770d2a0a17b7b82'));
        $this->assertFalse($client->isTheSameClient(new CardNumber('f9e33d75d79bf46e4873b4f920626888')));
    }
}
