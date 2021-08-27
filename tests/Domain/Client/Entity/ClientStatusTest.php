<?php

namespace App\Tests\Domain\Client\Entity;

use App\Domain\Client\Exception\InvalidStatusException;
use App\Domain\Client\Model\ClientStatus;
use PHPUnit\Framework\TestCase;

class ClientStatusTest extends TestCase
{
    public function testCorrectStatus(): void
    {
        $clientStatus = new ClientStatus(ClientStatus::ACTIVE);
        $this->assertEquals(ClientStatus::ACTIVE, (string) $clientStatus);
    }

    public function testInvalidStatus(): void
    {
        $this->expectException(InvalidStatusException::class);
        new ClientStatus('WRONG');
    }
}
