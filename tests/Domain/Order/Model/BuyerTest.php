<?php

namespace App\Tests\Domain\Order\Model;

use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\BuyerStatus;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use PHPUnit\Framework\TestCase;

class BuyerTest extends TestCase
{
    public function testIsActiveBuyerWhenBuyerHasOtherStatus(): void
    {
        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new PersonalName('Joe', 'Smith'),
            Gender::FEMALE(),
            BuyerStatus::NOT_ACTIVE()
        );

        $this->assertFalse($buyer->isActive());
    }

    public function testIsActiveBuyerWhenBuyerHasTheSameStatus(): void
    {
        $buyer = new Buyer(
            new CardNumber('3da8b78de7732860e770d2a0a17b7b82'),
            new PersonalName('Joe', 'Smith'),
            Gender::FEMALE(),
            BuyerStatus::ACTIVE()
        );

        $this->assertTrue($buyer->isActive());
    }
}
