<?php

namespace App\Tests\Infrastructure\Offer\Enum;

use App\Infrastructure\Offer\Enum\OfferTypeEnum;
use App\Infrastructure\Offer\Exception\InvalidOfferTypeException;
use PHPUnit\Framework\TestCase;

class OfferTypeEnumTest extends TestCase
{
    public function testCorrectOfferType(): void
    {
        $typeEnum = new OfferTypeEnum(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES);
        $this->assertEquals(OfferTypeEnum::TYPE_NUMBER_OF_ENTRIES, (string) $typeEnum);
    }

    public function testInvalidOfferType(): void
    {
        $this->expectException(InvalidOfferTypeException::class);
        new OfferTypeEnum('WRONG');
    }
}
