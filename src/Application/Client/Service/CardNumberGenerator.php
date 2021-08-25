<?php

declare(strict_types=1);

namespace App\Application\Client\Service;

use App\Application\Client\Exception\CardNumberGenerationFailedException;
use App\Domain\Client\Entity\CardNumber;
use App\Domain\Client\Repository\ClientRepository;

class CardNumberGenerator
{
    public const GENERATION_TRIES_LIMIT = 10;

    public function __construct(private ClientRepository $repository)
    {
    }

    public function generateNumber(): string
    {
        $cardNumber = bin2hex(random_bytes(CardNumber::NUMBER_LENGTH / 2));

        for ($tryNumber = 0; $tryNumber < self::GENERATION_TRIES_LIMIT; ++$tryNumber) {
            $client = $this->repository->getClientByCardNumber($cardNumber);
            if (null === $client) {
                return $cardNumber;
            }
        }

        throw new CardNumberGenerationFailedException('Can not generate free card number');
    }
}
