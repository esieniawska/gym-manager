<?php

namespace App\Tests\Infrastructure\User\Service;

use App\Domain\User\Entity\Password;
use App\Domain\User\Entity\PasswordHash;
use App\Infrastructure\User\Service\ArgonPasswordEncoder;
use PHPUnit\Framework\TestCase;

class ArgonPasswordEncoderTest extends TestCase
{
    public function testEncodePassword(): void
    {
        $encoder = new ArgonPasswordEncoder();
        $this->assertInstanceOf(PasswordHash::class, $encoder->encode(new Password('password')));
    }

    public function testValidCorrectPassword(): void
    {
        $encoder = new ArgonPasswordEncoder();
        $encodedPassword = $encoder->encode(new Password('password'));
        $this->assertTrue($encoder->isValid(new Password('password'), $encodedPassword));
    }
}
