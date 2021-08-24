<?php

namespace App\Tests\UI\User\Http\Controller;

use App\Application\User\Dto\AuthToken;
use App\Application\User\Dto\LoginData;
use App\Application\User\Exception\InvalidUserPasswordException;
use App\Application\User\Service\LoginService;
use App\Domain\Shared\Exception\StringIsToLongException;
use App\UI\User\Http\Controller\LoginController;
use App\UI\User\Http\Dto\LoginForm;
use App\UI\User\Http\Dto\LoginOutput;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginControllerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ValidatorInterface $validatorMock;
    private ObjectProphecy|LoginService $loginServiceMock;

    protected function setUp(): void
    {
        $this->validatorMock = $this->prophesize(ValidatorInterface::class);
        $this->loginServiceMock = $this->prophesize(LoginService::class);
    }

    public function testSuccessfulLogin(): void
    {
        $this->validatorMock->validate(Argument::type(LoginForm::class))->shouldBeCalled();
        $this->loginServiceMock
            ->login(Argument::type(LoginData::class))
            ->willReturn(new AuthToken('access-token'));

        $controller = new LoginController();
        $data = new LoginForm('joe@example.com', 'password');
        $result = $controller($data, $this->validatorMock->reveal(), $this->loginServiceMock->reveal());

        $this->assertInstanceOf(LoginOutput::class, $result);
        $this->assertEquals('access-token', $result->accessToken);
    }

    public function testLoginWhenInvalidPassword(): void
    {
        $this->validatorMock->validate(Argument::type(LoginForm::class))->shouldBeCalled();
        $this->loginServiceMock
            ->login(Argument::type(LoginData::class))
            ->willThrow(InvalidUserPasswordException::class);

        $controller = new LoginController();
        $data = new LoginForm('joe@example.com', 'password');
        $this->expectException(AccessDeniedHttpException::class);
        $controller($data, $this->validatorMock->reveal(), $this->loginServiceMock->reveal());
    }

    public function testLoginWhenPasswordIsToLong(): void
    {
        $this->validatorMock->validate(Argument::type(LoginForm::class))->shouldBeCalled();
        $this->loginServiceMock
            ->login(Argument::type(LoginData::class))
            ->willThrow(StringIsToLongException::class);

        $controller = new LoginController();
        $data = new LoginForm('joe@example.com', 'password');
        $this->expectException(BadRequestHttpException::class);
        $controller($data, $this->validatorMock->reveal(), $this->loginServiceMock->reveal());
    }
}
