<?php

namespace App\Tests\Infrastructure\Shared\Security\Service;

use App\Application\Shared\Security\Exception\JtwEncodeException;
use App\Infrastructure\Shared\Security\Service\JtwTokenEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class JtwTokenEncoderTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|JWTEncoderInterface $encoderMock;
    private JtwTokenEncoder $encoder;

    protected function setUp(): void
    {
        $this->encoderMock = $this->prophesize(JWTEncoderInterface::class);
        $this->encoder = new JtwTokenEncoder($this->encoderMock->reveal());
    }

    public function testEncode(): void
    {
        $this->encoderMock->encode(Argument::type('array'))->willReturn('jwt-token');
        $this->assertEquals('jwt-token', $this->encoder->encode(['sub' => 'user']));
    }

    public function testEncodeOnFailure(): void
    {
        $this->encoderMock->encode(Argument::type('array'))->willThrow(JWTEncodeFailureException::class);
        $this->expectException(JtwEncodeException::class);
        $this->encoder->encode(['sub' => 'user']);
    }
}
