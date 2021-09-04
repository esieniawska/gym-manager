<?php

namespace App\Tests\Application\Order\Validator;

use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Validator\OrderValidator;
use App\Domain\Client\Model\Client;
use App\Domain\Offer\Model\OfferName;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
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
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer'),
            new Money(3.33),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $client = $this->prophesize(Client::class);
        $client->getGender()->willReturn(Gender::FEMALE());

        $this->expectException(OrderFailedException::class);
        $this->validator->ensureIsClientCanBuyThisOffer($client->reveal(), $offer);
    }

    public function testEnsureIsClientCanBuyThisOfferWhenCorrectOrder(): void
    {
        $offer = new TicketOfferWithNumberOfDaysAndGender(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new OfferName('offer'),
            new Money(3.33),
            OfferStatus::ACTIVE(),
            new NumberOfDays(3),
            Gender::MALE()
        );

        $client = $this->prophesize(Client::class);
        $client->getGender()->willReturn(Gender::MALE())->shouldBeCalled();
        $this->validator->ensureIsClientCanBuyThisOffer($client->reveal(), $offer);
    }
}
