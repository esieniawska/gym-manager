<?php

namespace App\Tests\Application\Order\Validator;

use App\Application\Client\Dto\ClientDto;
use App\Application\Offer\Dto\OfferDto;
use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Validator\OrderValidator;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\ValueObject\Gender;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OrderValidatorTest extends TestCase
{
    use ProphecyTrait;

    private OrderValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new OrderValidator();
    }

    public function testEnsureIsClientCanBuyThisOfferWhenOfferHasOtherGender(): void
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

        $this->expectException(OrderFailedException::class);
        $this->validator->ensureIsClientCanBuyThisOffer($client, $offer);
    }

    public function testEnsureIsClientCanBuyThisOfferWhenCorrectOrder(): void
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

        $client = $this->prophesize(ClientDto::class);
        $client->getGender()->willReturn(Gender::MALE)->shouldBeCalled();
        $this->validator->ensureIsClientCanBuyThisOffer($client->reveal(), $offer);
    }
}
