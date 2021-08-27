<?php

declare(strict_types=1);

namespace App\UI\Shared\Converter;

use Doctrine\Common\Collections\ArrayCollection;

abstract class DtoCollectionConverter implements DtoConverter
{
    public function createHttpFromApplicationDtoCollection(ArrayCollection $collection): ArrayCollection
    {
        $result = new ArrayCollection();

        foreach ($collection as $element) {
            $result->add($this->createHttpFromApplicationDto($element));
        }

        return $result;
    }
}
