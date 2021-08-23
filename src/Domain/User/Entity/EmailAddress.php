<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Exception\StringIsToLongException;
use App\Domain\Shared\Model\StringValueObject;
use App\Domain\User\Exception\WrongEmailAddressException;

class EmailAddress extends StringValueObject
{
    /**
     * @throws WrongEmailAddressException
     * @throws StringIsToLongException
     */
    public function __construct(protected string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new WrongEmailAddressException(sprintf('%s is not correct email', $value));
        }

        parent::__construct($value);
    }
}
