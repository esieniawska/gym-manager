<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Repository;

use App\Domain\Order\Model\Order;
use App\Domain\Order\Repository\OrderRepository;
use App\Infrastructure\Order\Converter\DbOrderConverter;
use App\Infrastructure\Order\Entity\DbOrder;
use App\Infrastructure\Shared\Repository\DoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrderRepository extends DoctrineRepository implements OrderRepository
{
    public function __construct(ManagerRegistry $registry, DbOrderConverter $clientConverter)
    {
        parent::__construct($registry, DbOrder::class, $clientConverter);
    }

    public function addOrder(Order $order): void
    {
        $this->addEntity($order);
    }
}
