<?php

namespace App\Domain\GymPass\Repository;

use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\Shared\ValueObject\Uuid;

interface GymPassRepository
{
    public function nextIdentity(): Uuid;

    public function addGymPass(GymPass $gymPass): void;

    public function getGymPass(Uuid $id): ?GymPass;

    public function addLastGymPassEntering(GymPass $gymPass): void;

    public function updateGymPassDates(GymPassWithEndDate $gymPass): void;
}
