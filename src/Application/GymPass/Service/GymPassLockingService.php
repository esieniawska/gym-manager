<?php

declare(strict_types=1);

namespace App\Application\GymPass\Service;

use App\Application\GymPass\Dto\GymPassLockingDto;
use App\Application\GymPass\Dto\GymPassLockingResult;
use App\Application\GymPass\Exception\GymPassNotFoundException;
use App\Application\GymPass\Exception\InvalidGymPassTypeException;
use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;

class GymPassLockingService
{
    public function __construct(private GymPassRepository $repository)
    {
    }

    /**
     * @throws InvalidGymPassTypeException
     * @throws InactiveGymPassException
     * @throws GymPassNotFoundException
     */
    public function lockGymPass(GymPassLockingDto $dto): GymPassLockingResult
    {
        $gymPass = $this->repository->getGymPass(new Uuid($dto->getGymPassId()));

        if (null === $gymPass) {
            throw new GymPassNotFoundException();
        }

        if (!$gymPass instanceof GymPassWithEndDate) {
            throw new InvalidGymPassTypeException();
        }

        $gymPass->lockGymPass(new NumberOfDays($dto->getNumberOfDays()));
        $this->repository->updateGymPassDates($gymPass);

        return new GymPassLockingResult(
            $gymPass->getEndDate(),
            $gymPass->getLockStartDate(),
            $gymPass->getLockEndDate()
        );
    }
}
