<?php

namespace App\UI\Offer\Http\Dto;

class OfferType
{
    public const TYPE_NUMBER_OF_ENTRIES = 'TYPE_NUMBER_OF_ENTRIES';
    public const TYPE_NUMBER_OF_DAYS = 'TYPE_NUMBER_OF_DAYS';

    public const ALL = [
        self::TYPE_NUMBER_OF_ENTRIES,
        self::TYPE_NUMBER_OF_DAYS,
    ];
}
