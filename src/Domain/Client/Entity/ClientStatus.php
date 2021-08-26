<?php

declare(strict_types=1);

namespace App\Domain\Client\Entity;

use App\Domain\Client\Exception\InvalidStatusException;
use App\Domain\Shared\Model\StringValueObject;

class ClientStatus extends StringValueObject
{
    public const ACTIVE = 'ACTIVE';
    public const NOT_ACTIVE = 'NOT_ACTIVE';

    public const STATUSES = [
        self::ACTIVE,
        self::NOT_ACTIVE,
    ];

    public static function getStatuses(): array
    {
        return self::STATUSES;
    }

    public function __construct(protected string $status)
    {
        $this->validateStatus($status);
        parent::__construct($status);
    }

    private function validateStatus(string $gender): void
    {
        if (!in_array($gender, self::getStatuses())) {
            throw new InvalidStatusException('Invalid status');
        }
    }
}
