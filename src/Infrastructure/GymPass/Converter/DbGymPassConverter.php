<?php

declare(strict_types=1);

namespace App\Infrastructure\GymPass\Converter;

use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\Shared\Model\DomainModel;
use App\Infrastructure\GymPass\Entity\DbGymPass;
use App\Infrastructure\GymPass\Entity\DbGymPassWithEndDate;
use App\Infrastructure\GymPass\Entity\DbGymPassWithNumberOfEntries;
use App\Infrastructure\GymPass\Exception\InvalidGymPassTypeException;
use App\Infrastructure\Shared\Converter\DbCollectionConverter;
use App\Infrastructure\Shared\Entity\DbEntity;
use Ramsey\Uuid\Uuid as RamseyUuid;

class DbGymPassConverter extends DbCollectionConverter
{
    public function convertDomainObjectToDbModel(DomainModel $gymPass): DbGymPass
    {
        switch ($gymPass) {
            case $gymPass instanceof GymPassWithNumberOfEntries:
                return $this->createDbGymPassWithNumberOfEntries($gymPass);
            case $gymPass instanceof GymPassWithEndDate:
                return $this->createDbGymPassWithEndDate($gymPass);
            default:
                throw new InvalidGymPassTypeException('Invalid gym pass type.');
        }
    }

    private function createDbGymPassWithNumberOfEntries(GymPassWithNumberOfEntries $gymPass): DbGymPassWithNumberOfEntries
    {
        return new DbGymPassWithNumberOfEntries(
            RamseyUuid::fromString((string) $gymPass->getId()),
            (string) $gymPass->getClient()->getCardNumber(),
            $gymPass->getStartDate(),
            $gymPass->getNumberOfEntries()->getValue()
        );
    }

    private function createDbGymPassWithEndDate(GymPassWithEndDate $gymPass): DbGymPassWithEndDate
    {
        return new DbGymPassWithEndDate(
            RamseyUuid::fromString((string) $gymPass->getId()),
            (string) $gymPass->getClient()->getCardNumber(),
            $gymPass->getStartDate(),
            $gymPass->getEndDate()
        );
    }

    public function convertDbModelToDomainObject(DbEntity $dbEntity): DomainModel
    {
        // TODO: Implement convertDbModelToDomainObject() method.
    }
}
