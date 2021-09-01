<?php

namespace App\Tests\Domain\Offer\Model;

use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class OfferStatusTest extends TestCase
{
    public function testCorrectPackageStatus(): void
    {
        $status = new OfferStatus(OfferStatus::ACTIVE);
        $this->assertEquals(OfferStatus::ACTIVE, (string) $status);
    }

    public function testInvalidStatus(): void
    {
        $this->expectException(InvalidValueException::class);
        new OfferStatus('WRONG-STATUS');
    }
}
