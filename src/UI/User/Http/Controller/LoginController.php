<?php

namespace App\UI\User\Http\Controller;

use App\Application\User\Dto\LoginData;
use App\Application\User\Exception\InvalidUserPasswordException;
use App\Application\User\Exception\UserNotFoundException;
use App\Application\User\Service\LoginService;
use App\UI\User\Http\Dto\LoginForm;
use App\UI\User\Http\Dto\LoginOutput;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginController
{
    public function __invoke(LoginForm $data, ValidatorInterface $validator, LoginService $loginService)
    {
        $validator->validate($data);
        try {
            $authToken = $loginService->login(new LoginData($data->email, $data->password));

            return new LoginOutput($authToken->getAccessToken());
        } catch (UserNotFoundException|InvalidUserPasswordException $e) {
            throw new AccessDeniedHttpException();
        }
    }
}
