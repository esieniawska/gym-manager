<?php

namespace App\Application\Shared\Assembler;

use App\Application\Shared\Dto\BaseDto;
use App\Domain\Shared\Model\DomainModel;
use Doctrine\Common\Collections\ArrayCollection;

interface DtoAssembler
{
    public function assembleDomainObjectToDto(DomainModel $domainModel): BaseDto;

    public function assembleAll(ArrayCollection $collection): ArrayCollection;
}
