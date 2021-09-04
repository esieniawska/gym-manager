<?php

declare(strict_types=1);

namespace App\Domain\Offer\Model;

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
