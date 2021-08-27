<?php

namespace App\Tests\Domain\Shared\Model;

use App\Domain\Shared\Exception\InvalidUuidException;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    public function testSuccessfulCreate(): void
    {
        $uuid = new Uuid('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2');
        $this->assertEquals('0a536e85-6e8e-4aa4-ab6c-dffb48abd9e2', $uuid->getValue());
    }

    public function testFailedCreate(): void
    {
        $this->expectException(InvalidUuidException::class);
        new Uuid('0a536e85-6e8e-4aa');
    }
}
