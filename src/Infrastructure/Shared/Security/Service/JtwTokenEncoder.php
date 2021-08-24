<?php

namespace App\Infrastructure\Shared\Security\Service;

use App\Application\Shared\Security\Exception\JtwEncodeException;
use App\Application\Shared\Security\Service\JtwEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;

class JtwTokenEncoder implements JtwEncoder
{
    public function __construct(private JWTEncoderInterface $encoder)
    {
    }

    /**
     * @throws JtwEncodeException
     */
    public function encode(array $data): string
    {
        try {
            return $this->encoder->encode($data);
        } catch (JWTEncodeFailureException $exception) {
            throw new JtwEncodeException($exception->getMessage());
        }
    }
}
