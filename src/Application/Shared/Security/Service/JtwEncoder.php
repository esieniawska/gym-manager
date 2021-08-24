<?php

namespace App\Application\Shared\Security\Service;

use App\Application\Shared\Security\Exception\JtwEncodeException;

interface JtwEncoder
{
    /**
     * @throws JtwEncodeException
     */
    public function encode(array $data): string;
}
