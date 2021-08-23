<?php

namespace App\Tests\Application\User\Service;

use App\Application\User\Dto\RegisterUserDto;
use App\Application\User\Service\PasswordEncoder;
use App\Application\User\Service\RegistrationService;
use App\Domain\Shared\Model\StringValueObject;
use App\Domain\User\Entity\EmailAddress;
use App\Domain\User\Entity\Enum\UserRole;
use App\Domain\User\Entity\Password;
use App\Domain\User\Entity\PasswordHash;
use App\Domain\User\Entity\Roles;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class RegistrationServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|UserRepository $userRepositoryMock;
    private ObjectProphecy|PasswordEncoder $passwordEncoderMock;
    private RegistrationService $registrationService;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->prophesize(UserRepository::class);
        $this->passwordEncoderMock = $this->prophesize(PasswordEncoder::class);
        $this->registrationService = new RegistrationService(
            $this->userRepositoryMock->reveal(),
            $this->passwordEncoderMock->reveal()
        );
    }

    public function testSuccessfulRegisterAdmin(): void
    {
        $this->passwordEncoderMock
            ->encode(new Password('password'))
            ->willReturn(new PasswordHash('hash'));

        $this->userRepositoryMock->getByEmail('joe.wilsh@example.com')->willReturn(null);
        $this->userRepositoryMock->addUser(new User(
            new StringValueObject('Joe'),
            new StringValueObject('Wilsh'),
            new EmailAddress('joe.wilsh@example.com'),
            new PasswordHash('hash'),
            new Roles([UserRole::ROLE_ADMIN, UserRole::ROLE_USER])
        ))->shouldBeCalled();

        $dto = new RegisterUserDto(
            'joe.wilsh@example.com',
            'Joe',
            'Wilsh',
            'password'
        );

        $this->registrationService->registerAdmin($dto);
    }

    public function testNotRegisterAdminWhenToShortPassword(): void
    {
        $this->passwordEncoderMock
            ->encode(new Password('password'))
            ->willReturn(new PasswordHash('hash'));

        $this->userRepositoryMock->getByEmail('joe.wilsh@example.com')->willReturn(null);
        $this->userRepositoryMock->addUser(Argument::type(User::class))->shouldNotBeCalled();

        $dto = new RegisterUserDto(
            'joe.wilsh@example.com',
            'Joe',
            'Wilsh',
            'pass'
        );

        $this->expectErrorMessage('Password must have at least 8 characters');
        $this->registrationService->registerAdmin($dto);
    }

    public function testNotRegisterAdminWhenNotUniqueEmail(): void
    {
        $this->passwordEncoderMock
            ->encode(new Password('password'))
            ->willReturn(new PasswordHash('hash'));

        $user = $this->prophesize(User::class);
        $this->userRepositoryMock->getByEmail('joe.wilsh@example.com')->willReturn($user->reveal());
        $this->userRepositoryMock->addUser(Argument::type(User::class))->shouldNotBeCalled();

        $dto = new RegisterUserDto(
            'joe.wilsh@example.com',
            'Joe',
            'Wilsh',
            'password'
        );

        $this->expectErrorMessage('Email is not unique');
        $this->registrationService->registerAdmin($dto);
    }

    public function testNotRegisterAdminWhenWrongEmailAddress(): void
    {
        $this->passwordEncoderMock
            ->encode(new Password('password'))
            ->willReturn(new PasswordHash('hash'));

        $this->userRepositoryMock->getByEmail('example.com')->willReturn(null);
        $this->userRepositoryMock->addUser(Argument::type(User::class))->shouldNotBeCalled();

        $dto = new RegisterUserDto(
            'example.com',
            'Joe',
            'Wilsh',
            'password'
        );

        $this->expectErrorMessage('example.com is not correct email');
        $this->registrationService->registerAdmin($dto);
    }
}
