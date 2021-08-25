<?php

namespace App\Infrastructure\Shared\Converter;

use App\Domain\Shared\Model\DomainModel;
use App\Infrastructure\Shared\Entity\DbEntity;
use Doctrine\Common\Collections\ArrayCollection;

interface DbConverter
{
    public function convertDomainObjectToDbModel(DomainModel $domainModel): DbEntity;

    public function convertDbModelToDomainObject(DbEntity $dbEntity): DomainModel;

    public function convertAllDbModelToDomainObject(array $collection): ArrayCollection;
}
