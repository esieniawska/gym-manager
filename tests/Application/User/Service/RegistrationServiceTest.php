<?php

namespace App\Tests\Application\User\Service;

use App\Application\User\Dto\RegisterUserDto;
use App\Application\User\Service\PasswordEncoder;
use App\Application\User\Service\RegistrationService;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Model\Password;
use App\Domain\User\Model\PasswordHash;
use App\Domain\User\Model\Roles;
use App\Domain\User\Model\User;
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
        $this->userRepositoryMock
            ->nextIdentity()
            ->willReturn(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));

        $this->userRepositoryMock->addUser(new User(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Wilsh'),
            new EmailAddress('joe.wilsh@example.com'),
            new PasswordHash('hash'),
            new Roles([Roles::ROLE_ADMIN, Roles::ROLE_USER])
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
        $this->userRepositoryMock
            ->nextIdentity()
            ->willReturn(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));
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
