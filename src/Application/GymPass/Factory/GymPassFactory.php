<?php

declare(strict_types=1);

namespace App\Application\GymPass\Factory;

use App\Application\GymPass\Exception\InvalidOrderCreatedEventException;
use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\GymPass\Repository\GymPassRepository;
use App\Domain\Order\Event\OrderCreated;
use App\Domain\Order\Event\OrderForTicketNumberOfDaysCreated;
use App\Domain\Order\Event\OrderForTicketNumberOfEntriesCreated;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use DateInterval;
use DateTimeImmutable;

class GymPassFactory
{
    public function __construct(private GymPassRepository $repository)
    {
    }

    /**
     * @throws InvalidOrderCreatedEventException
     */
    public function createGymPassFromEvent(OrderCreated $orderCreated): GymPass
    {
        switch ($orderCreated) {
            case $orderCreated instanceof OrderForTicketNumberOfDaysCreated:
                return $this->createGymPassWithNumberOfDays($orderCreated);
            case $orderCreated instanceof OrderForTicketNumberOfEntriesCreated:
                return $this->createGymPassWithNumberOfEntries($orderCreated);
            default:
                throw new InvalidOrderCreatedEventException('Invalid OrderCreated event');
        }
    }

    private function createGymPassWithNumberOfDays(OrderForTicketNumberOfDaysCreated $event): GymPassWithEndDate
    {
        $endDate = (new DateTimeImmutable($event->getStartDate()->format('Y-m-d')))
            ->add(new DateInterval(sprintf('P%sD', $event->getNumberOfDays())));

        return new GymPassWithEndDate(
            $this->repository->nextIdentity(),
            new Client(new CardNumber($event->getBuyerCardNumber())),
            $event->getStartDate(),
            $endDate
        );
    }

    private function createGymPassWithNumberOfEntries(OrderForTicketNumberOfEntriesCreated $event): GymPassWithNumberOfEntries
    {
        return new GymPassWithNumberOfEntries(
            $this->repository->nextIdentity(),
            new Client(new CardNumber($event->getBuyerCardNumber())),
            $event->getStartDate(),
            new NumberOfEntries($event->getNumberOfEntries())
        );
    }
}
