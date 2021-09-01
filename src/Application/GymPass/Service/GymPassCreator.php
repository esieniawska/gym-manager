<?php

declare(strict_types=1);

namespace App\Application\GymPass\Service;

use App\Application\GymPass\Factory\GymPassFactory;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Order\Event\OrderCreated;

class GymPassCreator
{
    public function __construct(
        private GymPassFactory $gymPassFactory,
        private GymPassRepository $gymPassRepository
    ) {
    }

    public function create(OrderCreated $orderCreated): void
    {
        $gymPass = $this->gymPassFactory->createGymPassFromEvent($orderCreated);
        $this->gymPassRepository->addGymPass($gymPass);
    }
}
