<?php

declare(strict_types=1);

namespace App\Application\Order\Validator;

use App\Application\Client\Dto\ClientDto;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Specification\OfferGenderIsCorrectSpecification;
use App\Application\Order\Specification\OfferSpecificationValidator;
use App\Domain\Client\Model\Client;

class OrderValidator
{
    /**
     * @throws OrderFailedException
     */
    public function ensureIsClientCanBuyThisOffer(ClientDto $client, OfferDto $offerTicket)
    {
        $validators = $this->createValidators($client, $offerTicket);
        foreach ($validators as $validator) {
            $validator->checkIsValidOffer($offerTicket);
        }
    }

    private function createValidators(ClientDto $client, OfferDto $offerTicket): array
    {
        $validators = [];

        if (null !== $offerTicket->getGender()) {
            $validators[] = new OfferSpecificationValidator(new OfferGenderIsCorrectSpecification($client), 'Invalid gender');
        }

        if (OfferDto::TYPE_NUMBER_OF_DAYS === $offerTicket->getType()) {
            //check if client has gym pass in startDate
        }

        return $validators;
    }
}
