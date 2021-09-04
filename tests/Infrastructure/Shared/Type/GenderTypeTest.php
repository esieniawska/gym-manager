<?php

namespace App\Tests\Infrastructure\Shared\Type;

use App\Domain\Shared\ValueObject\Gender;
use App\Infrastructure\Shared\Type\GenderType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GenderTypeTest extends TestCase
{
    use ProphecyTrait;

    public function testConvertToPHPValueWhenValueIsString(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new GenderType();
        $result = $type->convertToPHPValue('MALE', $platform->reveal());
        $this->assertEquals(Gender::MALE(), $result);
    }

    public function testConvertToPHPValueWhenValueIsNull(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new GenderType();
        $result = $type->convertToPHPValue(null, $platform->reveal());
        $this->assertEmpty($result);
    }

    public function testConvertToDatabaseValueWhenValueIsNull(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new GenderType();
        $result = $type->convertToDatabaseValue(null, $platform->reveal());
        $this->assertEmpty($result);
    }

    public function testConvertToDatabaseValueWhenValueIsString(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new GenderType();
        $result = $type->convertToDatabaseValue(Gender::MALE(), $platform->reveal());
        $this->assertEquals('MALE', $result);
    }
}
