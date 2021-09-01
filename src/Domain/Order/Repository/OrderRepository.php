<?php

declare(strict_types=1);

namespace App\Domain\Order\Repository;

use App\Domain\Order\Model\Order;
use App\Domain\Shared\ValueObject\Uuid;

interface OrderRepository
{
    public function addOrder(Order $order): void;

    public function nextIdentity(): Uuid;
}
