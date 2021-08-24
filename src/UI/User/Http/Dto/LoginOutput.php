<?php

namespace App\UI\User\Http\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;

class LoginOutput
{
    #[ApiProperty(
        description: 'Access token',
        example: 'eyJ0eXUzI1NiJ9.eyJpYXQiOjE2Mjk3OTU0MjYsImV4cVbS5jb20ifQ.Zi7siAV9gYs2ZYUTpy0erddiaw'
    )]
    public string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }
}
