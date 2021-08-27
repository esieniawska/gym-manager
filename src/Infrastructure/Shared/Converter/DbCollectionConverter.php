<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Converter;

use Doctrine\Common\Collections\ArrayCollection;

abstract class DbCollectionConverter implements DbConverter
{
    public function convertAllDbModelToDomainObject(array $collection): ArrayCollection
    {
        $result = new ArrayCollection();

        foreach ($collection as $element) {
            $result->add($this->convertDbModelToDomainObject($element));
        }

        return $result;
    }
}
