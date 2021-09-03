<?php

namespace App\Tests\Infrastructure\Offer\Type;

use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Offer\Type\OfferType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OfferTypeTest extends TestCase
{
    use ProphecyTrait;

    public function testConvertToPHPValueWhenValueIsString(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OfferType();
        $result = $type->convertToPHPValue(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES, $platform->reveal());
        $this->assertEquals(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(), $result);
    }

    public function testConvertToPHPValueWhenValueIsNull(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OfferType();
        $result = $type->convertToPHPValue(null, $platform->reveal());
        $this->assertEmpty($result);
    }

    public function testConvertToDatabaseValueWhenValueIsNull(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OfferType();
        $result = $type->convertToDatabaseValue(null, $platform->reveal());
        $this->assertEmpty($result);
    }

    public function testConvertToDatabaseValueWhenValueIsString(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OfferType();
        $result = $type->convertToDatabaseValue(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES(), $platform->reveal());
        $this->assertEquals(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES, $result);
    }
}
