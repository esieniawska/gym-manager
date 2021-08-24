<?php

namespace App\Tests\Application\User\Service;

use App\Application\Shared\Security\Service\JtwEncoder;
use App\Application\User\Dto\AuthToken;
use App\Application\User\Dto\LoginData;
use App\Application\User\Exception\InvalidUserPasswordException;
use App\Application\User\Exception\UserNotFoundException;
use App\Application\User\Service\LoginService;
use App\Application\User\Service\PasswordEncoder;
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
        $this->loginService->login(new LoginData('joe@example.com', 'pass'));
    }

    public function testLoginWhenUserHasInvalidPassword(): void
    {
        $this->repositoryMock->getByEmail('joe@example.com')->willReturn($this->getUser());
        $this->passwordEncoderMock
            ->isValid(new Password('pass'), new PasswordHash('hash'))
            ->willReturn(false);
        $this->expectException(InvalidUserPasswordException::class);
        $this->loginService->login(new LoginData('joe@example.com', 'pass'));
    }

    public function testSuccessfulLogin(): void
    {
        $this->repositoryMock->getByEmail('joe@example.com')->willReturn($this->getUser());
        $this->passwordEncoderMock
            ->isValid(new Password('pass'), new PasswordHash('hash'))
            ->willReturn(true);

        $this->jtwEncoderMock->encode(Argument::type('array'))->willReturn('jwt-token');
        $result = $this->loginService->login(new LoginData('joe@example.com', 'pass'));
        $this->assertInstanceOf(AuthToken::class, $result);
        $this->assertEquals($result->getAccessToken(), 'jwt-token');
    }

    private function getUser(): User
    {
        return new User(
            new StringValueObject('Joe'),
            new StringValueObject('Smith'),
            new EmailAddress('joe@example.com'),
            new PasswordHash('hash'),
            new Roles([UserRole::ROLE_USER])
        );
    }
}
