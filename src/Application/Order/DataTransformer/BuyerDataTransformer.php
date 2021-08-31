<?php

declare(strict_types=1);

namespace App\Application\Order\DataTransformer;

use App\Domain\Client\ClientFacade;
use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\BuyerStatus;

class BuyerDataTransformer
{
    public function __construct(private ClientFacade $clientFacade)
    {
    }

    public function createBuyerFromClientCardNumber(string $cardNumber): Buyer
    {
        $client = $this->clientFacade->getClientByCardNumber($cardNumber);

        return new Buyer(
            $client->getCardNumber(),
            $client->getPersonalName(),
            $client->getGender(),
            new BuyerStatus((string) $client->getClientStatus())
        );
    }
}
