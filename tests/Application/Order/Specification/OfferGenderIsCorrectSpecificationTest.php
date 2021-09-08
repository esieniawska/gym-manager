<?php

namespace App\Tests\Application\Order\Specification;

use App\Application\Client\Dto\ClientDto;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Order\Specification\OfferGenderIsCorrectSpecification;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\ValueObject\Gender;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OfferGenderIsCorrectSpecificationTest extends TestCase
{
    use ProphecyTrait;

    public function testIsSatisfiedByWhenClientHasOtherGender(): void
    {
        $offer = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_DAYS,
            'name',
            0.05,
            OfferStatus::ACTIVE,
            4,
            Gender::MALE
        );

        $client = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::FEMALE,
            '123456789',
            'test@example.com'
        );
        $specification = new OfferGenderIsCorrectSpecification($client);
        $this->assertFalse($specification->isSatisfiedBy($offer));
    }

    public function testIsSatisfiedByWhenClientHasAcceptedGender(): void
    {
        $offer = new OfferDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            OfferDto::TYPE_NUMBER_OF_DAYS,
            'name',
            0.05,
            OfferStatus::ACTIVE,
            4,
            Gender::MALE
        );

        $client = new ClientDto(
            '7d24cece-b0c6-4657-95d5-31180ebfc8e1',
            '3da8b78de7732860e770d2a0a17b7b82',
            ClientStatus::ACTIVE,
            'Joe',
            'Smith',
            Gender::MALE,
            '123456789',
            'test@example.com'
        );
        $specification = new OfferGenderIsCorrectSpecification($client);
        $this->assertTrue($specification->isSatisfiedBy($offer));
    }
}
