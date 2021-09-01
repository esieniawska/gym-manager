<?php

declare(strict_types=1);

namespace App\Infrastructure\GymPass\Converter;

use App\Domain\Shared\Model\DomainModel;
use App\Infrastructure\Shared\Converter\DbCollectionConverter;
use App\Infrastructure\Shared\Entity\DbEntity;

class GymPassDbConverter extends DbCollectionConverter
{
    public function convertDomainObjectToDbModel(DomainModel $domainModel): DbEntity
    {
        // TODO: Implement convertDomainObjectToDbModel() method.
    }

    public function convertDbModelToDomainObject(DbEntity $dbEntity): DomainModel
    {
        // TODO: Implement convertDbModelToDomainObject() method.
    }
}
