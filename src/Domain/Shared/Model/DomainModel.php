<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

use App\Domain\Shared\ValueObject\Uuid;

abstract class DomainModel
{
    public function __construct(protected Uuid $id)
    {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
