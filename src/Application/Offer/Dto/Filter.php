<?php

declare(strict_types=1);

namespace App\Application\Offer\Dto;

class Filter
{
    public function __construct(
        private ?string $name = null,
        private ?string $status = null
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
