<?php

namespace App\UI\Shared\Converter;

use App\Application\Shared\Dto\BaseDto as ApplicationDto;
use App\UI\Shared\Dto\BaseDto as HttpDto;
use Doctrine\Common\Collections\ArrayCollection;

interface DtoConverter
{
    public function createHttpFromApplicationDto(ApplicationDto $dto): HttpDto;

    public function createHttpFromApplicationDtoCollection(ArrayCollection $collection): ArrayCollection;
}
