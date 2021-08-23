<?php

namespace App\Tests\Domain\Shared\Model;

use App\Domain\Shared\Exception\StringIsToLongException;
use App\Domain\Shared\Model\StringValueObject;
use PHPUnit\Framework\TestCase;

class StringValueObjectTest extends TestCase
{
    public function testCorrectValue(): void
    {
        $value = 'correct-string';
        $stringValue = new StringValueObject($value);
        $this->assertEquals($value, $stringValue->getValue());
    }

    public function testWrongValue(): void
    {
        $value = 'wrong-string';
        $this->expectException(StringIsToLongException::class);
        new StringValueObject($value, 5);
    }
}
