<?php

namespace App\Tests\Domain\Offer\Model;

use App\Domain\Offer\Model\OfferName;
use App\Domain\Shared\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class OfferNameTest extends TestCase
{
    public function testSuccessfulCreatePackageName(): void
    {
        $packageName = new OfferName('package');
        $this->assertEquals('package', (string) $packageName);
    }

    public function testFailedCreatePackageNameWhenTooShort(): void
    {
        $this->expectException(InvalidValueException::class);
        new OfferName('12');
    }
}
