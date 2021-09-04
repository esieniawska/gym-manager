<?php

namespace App\Tests\Application\Order\Specification;

use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Specification\OfferSpecification;
use App\Application\Order\Specification\OfferSpecificationValidator;
use App\Domain\Offer\Model\OfferTicket;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class OfferSpecificationValidatorTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferSpecification $specificationMock;
    private OfferSpecificationValidator $validator;

    protected function setUp(): void
    {
        $this->specificationMock = $this->prophesize(OfferSpecification::class);
        $this->validator = new OfferSpecificationValidator($this->specificationMock->reveal(), 'Invalid order');
    }

    public function testCheckIsValidOfferWhenOfferIsNotValid(): void
    {
        $this->specificationMock
            ->isSatisfiedBy(Argument::type(OfferTicket::class))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->expectException(OrderFailedException::class);
        $orderItem = $this->prophesize(TicketOfferWithNumberOfEntries::class);
        $this->validator->checkIsValidOffer($orderItem->reveal());
    }

    public function testCheckIsValidOfferWhenOfferIsValid(): void
    {
        $this->specificationMock
            ->isSatisfiedBy(Argument::type(OfferTicket::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $orderItem = $this->prophesize(TicketOfferWithNumberOfEntries::class);
        $this->validator->checkIsValidOffer($orderItem->reveal());
    }
}
