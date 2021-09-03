<?php

namespace App\Tests\UI\Offer\Converter;

use App\Application\Offer\Dto\OfferDto;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\ValueObject\Gender;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\Dto\OfferDto as HttpDto;
use PHPUnit\Framework\TestCase;

class OfferDtoConverterTest extends TestCase
{
    public function testCreateHttpFromApplicationDto()
    {
        $dto = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_ENTRIES,
            'offer-name',
            1.02,
            OfferStatus::ACTIVE,
            3,
            Gender::MALE
        );

        $converter = new OfferDtoConverter();
        $result = $converter->createHttpFromApplicationDto($dto);
        $this->assertInstanceOf(HttpDto::class, $result);
    }
}
