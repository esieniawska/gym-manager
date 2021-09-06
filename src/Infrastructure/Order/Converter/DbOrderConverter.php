<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Converter;

use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Model\TicketWithNumberOfDays;
use App\Domain\Order\Model\TicketWithNumberOfEntries;
use App\Domain\Shared\Model\DomainModel;
use App\Infrastructure\Order\Entity\DbOrder;
use App\Infrastructure\Order\Enum\OrderTypeEnum;
use App\Infrastructure\Order\Exception\InvalidOrderTypeException;
use App\Infrastructure\Shared\Converter\DbCollectionConverter;
use App\Infrastructure\Shared\Entity\DbEntity;
use Ramsey\Uuid\Uuid as RamseyUuid;

class DbOrderConverter extends DbCollectionConverter
{
    public function convertDomainObjectToDbModel(DomainModel $order): DbEntity
    {
        /* @var $order Order */
        return new DbOrder(
            RamseyUuid::fromString((string) $order->getId()),
            RamseyUuid::fromString((string) $order->getOrderItem()->getOfferId()),
            (string) $order->getBuyer()->getCardNumber(),
            $this->getDbType($order->getOrderItem()),
            $order->getOrderItem()->getPrice()->getValue(),
            $order->getOrderItem()->getQuantity()->getValue(),
            $order->getStartDate(),
            $order->getCreatedAt()
        );
    }

    public function convertDbModelToDomainObject(DbEntity $dbEntity): DomainModel
    {
        // TODO: Implement convertDbModelToDomainObject() method.
    }

    private function getDbType(OrderItem $ticket): OrderTypeEnum
    {
        switch ($ticket) {
            case $ticket instanceof TicketWithNumberOfDays:
                return OrderTypeEnum::TYPE_NUMBER_OF_DAYS();
            case $ticket instanceof TicketWithNumberOfEntries:
                return OrderTypeEnum::TYPE_NUMBER_OF_ENTRIES();
            default:
                throw new InvalidOrderTypeException('Invalid order type');
        }
    }
}
