<?php

declare(strict_types=1);

namespace App\Application\User\Service;

use App\Application\User\Dto\RegisterUserDto;
use App\Application\User\Exception\RegistrationFailedException;
use App\Domain\Shared\Exception\InvalidEmailAddressException;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\User\Model\Password;
use App\Domain\User\Model\Roles;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Specification\EmailIsUniqueSpecification;
use App\Domain\User\Specification\PasswordMinLengthSpecification;

class RegistrationService
{
    private const MIN_LENGTH = 8;

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
        } catch (InvalidEmailAddressException $exception) {
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
        $this->passwordMustHaveMinCharacters($registerUserDto->getPassword());
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

    /**
     * @throws RegistrationFailedException
     */
    private function passwordMustHaveMinCharacters(string $password): void
    {
        $specification = new PasswordMinLengthSpecification(self::MIN_LENGTH);
        if (!$specification->isSatisfiedBy($password)) {
            throw new RegistrationFailedException(sprintf('Password must have at least %s characters', self::MIN_LENGTH));
        }
    }
}
