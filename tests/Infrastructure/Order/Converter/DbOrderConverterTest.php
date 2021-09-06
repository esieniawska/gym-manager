<?php

namespace App\Tests\Infrastructure\Order\Converter;

use App\Domain\Order\Model\Buyer;
use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\Ticket;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\Money;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Order\Converter\DbOrderConverter;
use App\Infrastructure\Order\Entity\DbOrder;
use App\Infrastructure\Order\Exception\InvalidOrderTypeException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DbOrderConverterTest extends TestCase
{
    use ProphecyTrait;

    public function testConvertDomainObjectTicketWithNumberOfDaysToDbModel(): void
    {
        $orderItem = new TicketWithNumberOfDays(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            new NumberOfDays(3)
        );

        $order = new Order(
            new Uuid('300bff1c-171d-4065-bf76-97ca98574667'),
            new Buyer(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $converter = new DbOrderConverter();
        $result = $converter->convertDomainObjectToDbModel($order);
        $this->assertInstanceOf(DbOrder::class, $result);
    }

    public function testConvertDomainObjectTicketWithNumberOfEntriesToDbModel(): void
    {
        $orderItem = new TicketWithNumberOfEntries(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Money(3.33),
            new NumberOfEntries(3)
        );

        $order = new Order(
            new Uuid('300bff1c-171d-4065-bf76-97ca98574667'),
            new Buyer(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            $orderItem,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $converter = new DbOrderConverter();
        $result = $converter->convertDomainObjectToDbModel($order);
        $this->assertInstanceOf(DbOrder::class, $result);
    }

    public function testConvertDomainObjectDbModelWhenInvalidType(): void
    {
        $orderItem = $this->prophesize(Ticket::class);
        $orderItem->getOfferId()->willReturn(new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'));

        $order = new Order(
            new Uuid('300bff1c-171d-4065-bf76-97ca98574667'),
            new Buyer(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            $orderItem->reveal(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $this->expectException(InvalidOrderTypeException::class);
        $converter = new DbOrderConverter();
        $converter->convertDomainObjectToDbModel($order);
    }
}
