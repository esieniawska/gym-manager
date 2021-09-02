<?php

namespace App\Tests\UI\Offer\Http\Factory;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\CreateNumberOfEntriesOfferDto;
use App\UI\Offer\Exception\InvalidOfferTypeException;
use App\UI\Offer\Http\Dto\OfferDto;
use App\UI\Offer\Http\Dto\OfferType;
use App\UI\Offer\Http\Factory\CreateOfferDtoFactory;
use PHPUnit\Framework\TestCase;

class CreateOfferDtoFactoryTest extends TestCase
{
    public function testCreateNumberOfDaysOfferDtoFromHttp(): void
    {
        $dto = (new OfferDto())
            ->setName('offer-name')
            ->setPrice(1.3)
            ->setQuantity(11)
            ->setType(OfferType::TYPE_NUMBER_OF_DAYS);
        $factory = new CreateOfferDtoFactory();
        $result = $factory->createDtoFromHttp($dto);
        $this->assertInstanceOf(CreateNumberOfDaysOfferDto::class, $result);
    }

    public function testCreateNumberOfEntriesOfferDtoFromHttp(): void
    {
        $dto = (new OfferDto())
            ->setName('offer-name')
            ->setPrice(1.3)
            ->setQuantity(11)
            ->setType(OfferType::TYPE_NUMBER_OF_ENTRIES);
        $factory = new CreateOfferDtoFactory();
        $result = $factory->createDtoFromHttp($dto);
        $this->assertInstanceOf(CreateNumberOfEntriesOfferDto::class, $result);
    }

    public function testTryCreateDtoWhenEmptyType(): void
    {
        $dto = (new OfferDto())
            ->setName('offer-name')
            ->setPrice(1.3)
            ->setQuantity(11)
        ->setType('wrong');
        $factory = new CreateOfferDtoFactory();
        $this->expectException(InvalidOfferTypeException::class);
        $factory->createDtoFromHttp($dto);
    }
}
