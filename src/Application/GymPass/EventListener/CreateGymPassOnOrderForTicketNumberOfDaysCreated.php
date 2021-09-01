<?php

declare(strict_types=1);

namespace App\Application\GymPass\EventListener;

use App\Application\GymPass\Service\GymPassCreator;
use App\Domain\Order\Event\OrderForTicketNumberOfDaysCreated;

class CreateGymPassOnOrderForTicketNumberOfDaysCreated
{
    public function __construct(private GymPassCreator $creator)
    {
    }

    public function __invoke(OrderForTicketNumberOfDaysCreated $orderCreated)
    {
        $this->creator->create($orderCreated);
    }
}
