<?php

declare(strict_types=1);

namespace App\Application\Shared\Assembler;

use Doctrine\Common\Collections\ArrayCollection;

abstract class BaseDtoAssembler implements DtoAssembler
{
    public function assembleAll(ArrayCollection $collection): ArrayCollection
    {
        $result = new ArrayCollection();

        foreach ($collection as $element) {
            $result->add($this->assembleDomainObjectToDto($element));
        }

        return $result;
    }
}
