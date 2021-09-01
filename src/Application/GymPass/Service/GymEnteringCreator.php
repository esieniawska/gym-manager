<?php

declare(strict_types=1);

namespace App\Application\GymPass\Service;

use App\Application\GymPass\Dto\AddGymEnteringDto;
use App\Application\GymPass\Exception\GymPassNotFoundException;
use App\Application\GymPass\Exception\InvalidGymPassClientException;
use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\Domain\GymPass\Model\GymEntering;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Uuid;

class GymEnteringCreator
{
    public function __construct(private GymPassRepository $repository)
    {
    }

    /**
     * @throws InvalidGymPassClientException
     * @throws InactiveGymPassException
     * @throws GymPassNotFoundException
     */
    public function create(AddGymEnteringDto $dto): void
    {
        $gymPass = $this->repository->getGymPass(new Uuid($dto->getGymPassId()));

        if (null === $gymPass) {
            throw new GymPassNotFoundException();
        }

        if (!$gymPass->getClient()->isTheSameClient(new CardNumber($dto->getCardNumber()))) {
            throw new InvalidGymPassClientException();
        }

        $gymPass->addGymEntering(new GymEntering(new \DateTimeImmutable()));
        $this->repository->updateGymPassEntries($gymPass);
    }
}
