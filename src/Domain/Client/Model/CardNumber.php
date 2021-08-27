<?php

declare(strict_types=1);

namespace App\Domain\Client\Model;

use App\Domain\Client\Exception\InvalidCardNumberException;
use App\Domain\Shared\ValueObject\StringValueObject;

class CardNumber extends StringValueObject
{
    public const NUMBER_LENGTH = 32;
    public const NUMBER_PATTERN = '/^[0-9a-f]{32}$/';

    public function __construct(protected string $value)
    {
        $this->checkIsValidCardNumber($value);
        parent::__construct($value);
    }

    private function checkIsValidCardNumber(string $value): void
    {
        if (1 !== preg_match(self::NUMBER_PATTERN, $value)) {
            throw new InvalidCardNumberException('Invalid card number');
        }
    }
}
