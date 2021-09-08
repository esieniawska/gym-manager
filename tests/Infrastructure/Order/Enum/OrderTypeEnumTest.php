<?php

namespace App\Tests\Infrastructure\Order\Enum;

use App\Infrastructure\Order\Enum\OrderTypeEnum;
use App\Infrastructure\Order\Exception\InvalidOrderTypeException;
use PHPUnit\Framework\TestCase;

class OrderTypeEnumTest extends TestCase
{
    public function testCorrectOrderType(): void
    {
        $typeEnum = new OrderTypeEnum(OrderTypeEnum::TYPE_NUMBER_OF_ENTRIES);
        $this->assertEquals(OrderTypeEnum::TYPE_NUMBER_OF_ENTRIES, (string) $typeEnum);
    }

    public function testInvalidOrderType(): void
    {
        $this->expectException(InvalidOrderTypeException::class);
        new OrderTypeEnum('WRONG');
    }
}
