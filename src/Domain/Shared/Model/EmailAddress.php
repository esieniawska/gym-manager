<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

use App\Domain\Shared\Exception\InvalidEmailAddressException;
use App\Domain\Shared\Exception\StringIsToLongException;

class EmailAddress extends StringValueObject
{
    /**
     * @throws InvalidEmailAddressException
     * @throws StringIsToLongException
     */
    public function __construct(protected string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException(sprintf('%s is not correct email', $value));
        }

        parent::__construct($value);
    }
}
