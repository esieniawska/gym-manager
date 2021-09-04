<?php

namespace App\Tests\Infrastructure\Offer\Type;

use App\Domain\Offer\Model\OfferStatus;
use App\Infrastructure\Offer\Type\OfferStatusType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OfferStatusTypeTest extends TestCase
{
    use ProphecyTrait;

    public function testConvertToPHPValueWhenValueIsString(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OfferStatusType();
        $result = $type->convertToPHPValue(OfferStatus::ACTIVE, $platform->reveal());
        $this->assertEquals(OfferStatus::ACTIVE(), $result);
    }

    public function testConvertToPHPValueWhenValueIsNull(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OfferStatusType();
        $result = $type->convertToPHPValue(null, $platform->reveal());
        $this->assertEmpty($result);
    }

    public function testConvertToDatabaseValueWhenValueIsNull(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OfferStatusType();
        $result = $type->convertToDatabaseValue(null, $platform->reveal());
        $this->assertEmpty($result);
    }

    public function testConvertToDatabaseValueWhenValueIsString(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OfferStatusType();
        $result = $type->convertToDatabaseValue(OfferStatus::ACTIVE(), $platform->reveal());
        $this->assertEquals(OfferStatus::ACTIVE, $result);
    }
}
