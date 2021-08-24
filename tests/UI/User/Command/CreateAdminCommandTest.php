<?php

namespace App\Tests\UI\User\Command;

use App\Application\User\Dto\RegisterUserDto;
use App\Application\User\Exception\RegistrationFailedException;
use App\Application\User\Service\RegistrationService;
use App\UI\User\Command\CreateAdminCommand;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminCommandTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|RegistrationService $registrationServiceMock;
    private CreateAdminCommand $command;

    protected function setUp(): void
    {
        $this->registrationServiceMock = $this->prophesize(RegistrationService::class);
        $this->command = new CreateAdminCommand($this->registrationServiceMock->reveal());
    }

    public function testSuccessfulExecute(): void
    {
        $inputMock = $this->prophesize(InputInterface::class);
        $inputMock->getArgument('email')->willReturn('test@example.com');
        $inputMock->getArgument('first-name')->willReturn('Joe');
        $inputMock->getArgument('last-name')->willReturn('Smith');
        $inputMock->getArgument('password')->willReturn('password');

        $outputMock = $this->prophesize(OutputInterface::class);
        $dto = new RegisterUserDto(
            'test@example.com',
            'Joe',
            'Smith',
            'password'
        );

        $this->registrationServiceMock->registerAdmin($dto)->shouldBeCalled();
        $this->command->execute($inputMock->reveal(), $outputMock->reveal());
    }

    public function testExecuteWhenRegistrationException(): void
    {
        $inputMock = $this->prophesize(InputInterface::class);
        $inputMock->getArgument('email')->willReturn('test@example.com');
        $inputMock->getArgument('first-name')->willReturn('Joe');
        $inputMock->getArgument('last-name')->willReturn('Smith');
        $inputMock->getArgument('password')->willReturn('password');

        $outputMock = $this->prophesize(OutputInterface::class);
        $dto = new RegisterUserDto(
            'test@example.com',
            'Joe',
            'Smith',
            'password'
        );

        $this->registrationServiceMock
            ->registerAdmin($dto)
            ->willThrow(RegistrationFailedException::class)
            ->shouldBeCalled();

        $this->command->execute($inputMock->reveal(), $outputMock->reveal());
    }
}
