<?php

declare(strict_types=1);

namespace App\Infrastructure\GymPass\Converter;

use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
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
        /** @var $dbEntity DbGymPass */
        switch ($dbEntity) {
            case $dbEntity instanceof DbGymPassWithNumberOfEntries:
                return $this->createGymPassWithNumberOfEntries($dbEntity);
            case $dbEntity instanceof DbGymPassWithEndDate:
                return $this->createGymPassWithEndDate($dbEntity);
            default:
                throw new InvalidGymPassTypeException('Invalid gym pass type.');
        }
    }

    private function createGymPassWithNumberOfEntries(DbGymPassWithNumberOfEntries $dbModel): GymPassWithNumberOfEntries
    {
        return new GymPassWithNumberOfEntries(
            new Uuid($dbModel->getId()->toString()),
            new Client(new CardNumber($dbModel->getBuyerCardNumber())),
            $dbModel->getStartDate(),
            new NumberOfEntries($dbModel->getNumberOfEntries()),
            $this->createGymEntries($dbModel->getGymEnteringList())
        );
    }

    private function createGymPassWithEndDate(DbGymPassWithEndDate $dbModel): GymPassWithEndDate
    {
        return new GymPassWithEndDate(
            new Uuid($dbModel->getId()->toString()),
            new Client(new CardNumber($dbModel->getBuyerCardNumber())),
            $dbModel->getStartDate(),
            $dbModel->getEndDate(),
            $dbModel->getLockStartDate(),
            $dbModel->getLockEndDate(),
            $this->createGymEntries($dbModel->getGymEnteringList())
        );
    }

    private function createGymEntries(array $dbGymEnteringList): array
    {
        $gymEntries = [];
        foreach ($dbGymEnteringList as $dbModel) {
            $gymEntries[] = $dbModel->getDate();
        }

        return $gymEntries;
    }
}
