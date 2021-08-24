<?php

namespace App\Application\User\Dto;

class AuthToken
{
    public function __construct(private string $accessToken)
    {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
