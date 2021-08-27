<?php

declare(strict_types=1);

namespace App\Domain\Client\Model;

use App\Domain\Client\Exception\InvalidStatusException;
use App\Domain\Shared\ValueObject\Enum;

/**
 * @method static ClientStatus ACTIVE()
 * @method static ClientStatus NOT_ACTIVE()
 * @method static ClientStatus fromString(string $value)
 */
class ClientStatus extends Enum
{
    public const ACTIVE = 'ACTIVE';
    public const NOT_ACTIVE = 'NOT_ACTIVE';

    public const ALL = [
        self::ACTIVE,
        self::NOT_ACTIVE,
    ];

    public function __construct(protected string $status)
    {
        parent::__construct($status);
    }

    protected static function throwExceptionForInvalidValue(string $value): void
    {
        throw new InvalidStatusException("Invalid status: $value");
    }
}
