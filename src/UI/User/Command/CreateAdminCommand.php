<?php

declare(strict_types=1);

namespace App\UI\User\Command;

use App\Application\User\Dto\RegisterUserDto;
use App\Application\User\Exception\RegistrationFailedException;
use App\Application\User\Service\RegistrationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'user:create-admin';

    public function __construct(private RegistrationService $registrationService)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create admin')
            ->addArgument('email', InputArgument::REQUIRED, 'User\'s email.')
            ->addArgument('password', InputArgument::REQUIRED, 'User\'s password.')
            ->addArgument('first-name', InputArgument::REQUIRED, 'User\'s first name.')
            ->addArgument('last-name', InputArgument::REQUIRED, 'User\'s last name.');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $dto = new RegisterUserDto(
            $input->getArgument('email'),
            $input->getArgument('first-name'),
            $input->getArgument('last-name'),
            $input->getArgument('password')
        );

        try {
            $this->registrationService->registerAdmin($dto);
            $output->writeln('Admin created');
        } catch (RegistrationFailedException $exception) {
            $output->writeln($exception->getMessage());
        }

        return 0;
    }
}
