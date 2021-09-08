<?php

namespace App\Tests\Infrastructure\Order\Type;

use App\Infrastructure\Order\Enum\OrderTypeEnum;
use App\Infrastructure\Order\Type\OrderType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OrderTypeTest extends TestCase
{
    use ProphecyTrait;

    public function testConvertToPHPValueWhenValueIsString(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OrderType();
        $result = $type->convertToPHPValue('TYPE_NUMBER_OF_ENTRIES', $platform->reveal());
        $this->assertEquals(OrderTypeEnum::TYPE_NUMBER_OF_ENTRIES(), $result);
    }

    public function testConvertToPHPValueWhenValueIsNull(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OrderType();
        $result = $type->convertToPHPValue(null, $platform->reveal());
        $this->assertEmpty($result);
    }

    public function testConvertToDatabaseValueWhenValueIsNull(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OrderType();
        $result = $type->convertToDatabaseValue(null, $platform->reveal());
        $this->assertEmpty($result);
    }

    public function testConvertToDatabaseValueWhenValueIsString(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new OrderType();
        $result = $type->convertToDatabaseValue(OrderTypeEnum::TYPE_NUMBER_OF_ENTRIES(), $platform->reveal());
        $this->assertEquals('TYPE_NUMBER_OF_ENTRIES', $result);
    }
}
