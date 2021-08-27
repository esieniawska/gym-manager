<?php

namespace App\Application\User\Service;

use App\Application\Shared\Security\Exception\JtwEncodeException;
use App\Application\Shared\Security\Service\JtwEncoder;
use App\Application\User\Dto\AuthToken;
use App\Application\User\Dto\LoginData;
use App\Application\User\Exception\InvalidUserPasswordException;
use App\Application\User\Exception\UserNotFoundException;
use App\Domain\User\Model\Password;
use App\Domain\User\Model\PasswordHash;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepository;
use DateTimeImmutable;

class LoginService
{
    public function __construct(
        private int $tokenLifetime,
        private UserRepository $repository,
        private PasswordEncoder $passwordEncoder,
        private JtwEncoder $jtwEncoder
    ) {
    }

    /**
     * @throws InvalidUserPasswordException
     * @throws UserNotFoundException
     * @throws JtwEncodeException
     */
    public function login(LoginData $dto): AuthToken
    {
        $user = $this->getUserByEmail($dto->getEmail());
        $this->checkIsValidPassword(new Password($dto->getPassword()), $user->getPasswordHash());

        return new AuthToken($this->createAccessToken($user));
    }

    /**
     * @throws UserNotFoundException
     */
    private function getUserByEmail(string $email): User
    {
        $user = $this->repository->getByEmail($email);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @throws InvalidUserPasswordException
     */
    private function checkIsValidPassword(Password $password, PasswordHash $passwordHash): void
    {
        if (!$this->passwordEncoder->isValid($password, $passwordHash)) {
            throw new InvalidUserPasswordException();
        }
    }

    /**
     * @throws JtwEncodeException
     */
    private function createAccessToken(User $user): string
    {
        $payload = [
            'sub' => $user->getId()->getValue(),
            'exp' => new DateTimeImmutable('+ '.$this->tokenLifetime.'minutes'),
            'username' => $user->getEmail()->getValue(),
        ];

        return $this->jtwEncoder->encode($payload);
    }
}
