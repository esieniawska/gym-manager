<?php

namespace App\Tests\Application\User\Service;

use App\Application\Shared\Security\Service\JtwEncoder;
use App\Application\User\Dto\AuthToken;
use App\Application\User\Dto\LoginData;
use App\Application\User\Exception\InvalidUserPasswordException;
use App\Application\User\Exception\UserNotFoundException;
use App\Application\User\Service\LoginService;
use App\Application\User\Service\PasswordEncoder;
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

class LoginServiceTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|UserRepository $repositoryMock;
    private ObjectProphecy|PasswordEncoder $passwordEncoderMock;
    private ObjectProphecy|JtwEncoder $jtwEncoderMock;
    private LoginService $loginService;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->prophesize(UserRepository::class);
        $this->passwordEncoderMock = $this->prophesize(PasswordEncoder::class);
        $this->jtwEncoderMock = $this->prophesize(JtwEncoder::class);
        $this->loginService = new LoginService(
            10,
            $this->repositoryMock->reveal(),
            $this->passwordEncoderMock->reveal(),
            $this->jtwEncoderMock->reveal()
        );
    }

    public function testLoginWhenUserNotFound(): void
    {
        $this->repositoryMock->getByEmail('joe@example.com')->willReturn(null);
        $this->expectException(UserNotFoundException::class);
        $this->loginService->login(new LoginData('joe@example.com', 'password'));
    }

    public function testLoginWhenUserHasInvalidPassword(): void
    {
        $this->repositoryMock->getByEmail('joe@example.com')->willReturn($this->getUser());
        $this->passwordEncoderMock
            ->isValid(new Password('password'), new PasswordHash('hash'))
            ->willReturn(false);
        $this->expectException(InvalidUserPasswordException::class);
        $this->loginService->login(new LoginData('joe@example.com', 'password'));
    }

    public function testSuccessfulLogin(): void
    {
        $this->repositoryMock->getByEmail('joe@example.com')->willReturn($this->getUser());
        $this->passwordEncoderMock
            ->isValid(new Password('password'), new PasswordHash('hash'))
            ->willReturn(true);

        $this->jtwEncoderMock->encode(Argument::type('array'))->willReturn('jwt-token');
        $result = $this->loginService->login(new LoginData('joe@example.com', 'password'));
        $this->assertInstanceOf(AuthToken::class, $result);
        $this->assertEquals($result->getAccessToken(), 'jwt-token');
    }

    private function getUser(): User
    {
        return new User(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new PersonalName('Joe', 'Smith'),
            new EmailAddress('joe@example.com'),
            new PasswordHash('hash'),
            new Roles([Roles::ROLE_USER])
        );
    }
}
