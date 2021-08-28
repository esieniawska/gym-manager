<?php

declare(strict_types=1);

namespace App\Application\User\Service;

use App\Application\User\Dto\RegisterUserDto;
use App\Application\User\Exception\RegistrationFailedException;
use App\Domain\Shared\Exception\InvalidValueException;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\User\Model\Password;
use App\Domain\User\Model\Roles;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Specification\EmailIsUniqueSpecification;

class RegistrationService
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordEncoder $passwordEncoder
    ) {
    }

    /**
     * @throws RegistrationFailedException
     */
    public function registerAdmin(RegisterUserDto $registerUserDto): void
    {
        $this->validateUserData($registerUserDto);

        try {
            $user = new User(
                $this->userRepository->nextIdentity(),
                new PersonalName($registerUserDto->getFirstName(), $registerUserDto->getLastName()),
                new EmailAddress($registerUserDto->getEmail()),
                $this->passwordEncoder->encode(new Password($registerUserDto->getPassword())),
                new Roles([Roles::ROLE_ADMIN, Roles::ROLE_USER]),
            );
        } catch (InvalidValueException $exception) {
            throw new RegistrationFailedException($exception->getMessage());
        }

        $this->userRepository->addUser($user);
    }

    /**
     * @throws RegistrationFailedException
     */
    private function validateUserData(RegisterUserDto $registerUserDto): void
    {
        $this->emailMustBeUnique($registerUserDto->getEmail());
    }

    /**
     * @throws RegistrationFailedException
     */
    private function emailMustBeUnique(string $email): void
    {
        $specification = new EmailIsUniqueSpecification($this->userRepository);
        if (!$specification->isSatisfiedBy($email)) {
            throw new RegistrationFailedException('Email is not unique');
        }
    }
}
