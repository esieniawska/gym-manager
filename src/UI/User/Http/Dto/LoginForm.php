<?php

namespace App\UI\User\Http\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'path' => '/login',
            'status' => 200,
            'output' => 'App\UI\User\Http\Dto\LoginOutput',
            'controller' => 'App\UI\User\Http\Controller\LoginController',
            'openapi_context' => [
                'tags' => ['User'],
                'summary' => 'Logs the user into the application',
            ],
        ],
    ],
    itemOperations: [],
)]
class LoginForm
{
    #[ApiProperty(
        description: 'User email',
        identifier: true,
        example: 'admin@gym.com'
    )]
    #[Assert\NotBlank]
    public string $email;

    #[ApiProperty(
        description: 'User password',
        example: 'password'
    )]
    #[Assert\NotBlank]
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
