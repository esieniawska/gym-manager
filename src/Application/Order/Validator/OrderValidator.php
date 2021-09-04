<?php

declare(strict_types=1);

namespace App\Application\Order\Validator;

use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Specification\OfferGenderIsCorrectSpecification;
use App\Application\Order\Specification\OfferSpecificationValidator;
use App\Domain\Client\Model\Client;
use App\Domain\Offer\Model\GenderOfferTicket;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\OfferWithNumberOfDays;

class OrderValidator
{
    /**
     * @throws OrderFailedException
     */
    public function ensureIsClientCanBuyThisOffer(Client $client, OfferTicket $offerTicket)
    {
        $validators = $this->createValidators($client, $offerTicket);
        foreach ($validators as $validator) {
            $validator->checkIsValidOffer($offerTicket);
        }
    }

    private function createValidators(Client $client, OfferTicket $offerTicket): array
    {
        $validators = [];

        if ($offerTicket instanceof GenderOfferTicket) {
            $validators[] = new OfferSpecificationValidator(new OfferGenderIsCorrectSpecification($client), 'Invalid gender');
        }

        if ($offerTicket instanceof OfferWithNumberOfDays) {
            //check if client has gym pass in startDate
        }

        return $validators;
    }
}
