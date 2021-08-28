<?php

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidGenderException;
use App\Domain\Shared\ValueObject\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    public function testCorrectGender(): void
    {
        $gender = new Gender(Gender::FEMALE);
        $this->assertEquals(Gender::FEMALE, (string) $gender);
    }

    public function testInvalidGender(): void
    {
        $this->expectException(InvalidGenderException::class);
        new Gender('WRONG');
    }
}
