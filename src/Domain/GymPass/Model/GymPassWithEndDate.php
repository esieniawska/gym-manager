<?php

declare(strict_types=1);

namespace App\Domain\GymPass\Model;

use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\Domain\Shared\ValueObject\NumberOfDays;
use App\Domain\Shared\ValueObject\Uuid;
use DateInterval;

class GymPassWithEndDate extends GymPass
{
    public function __construct(
        protected Uuid $id,
        protected Client $client,
        protected \DateTimeImmutable $startDate,
        protected \DateTimeImmutable $endDate,
        private ?\DateTimeImmutable $lockStartDate = null,
        private ?\DateTimeImmutable $lockEndDate = null,
        protected array $gymEntering = []
    ) {
        parent::__construct($id, $client, $startDate, $gymEntering);
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    protected function isActive(\DateTimeImmutable $currentDate): bool
    {
        $endDateIsGreaterOrEqualsCurrentDate = $currentDate->getTimestamp() <= $this->endDate->getTimestamp();
        $currentDateDoesNotBetweenFreezingDates = $this->getIsDateDoesNotBetweenFreezingDates($currentDate);

        return $endDateIsGreaterOrEqualsCurrentDate && $currentDateDoesNotBetweenFreezingDates;
    }

    private function getIsDateDoesNotBetweenFreezingDates(\DateTimeImmutable $currentDate): bool
    {
        if (null === $this->lockStartDate || null === $this->lockEndDate) {
            return true;
        }

        $dateIsGreaterThanEndDate = $currentDate->getTimestamp() > $this->lockEndDate->getTimestamp();
        $dateIsLessThanStartDate = $currentDate->getTimestamp() > $this->lockStartDate->getTimestamp();

        return $dateIsGreaterThanEndDate && $dateIsLessThanStartDate;
    }

    /**
     * @throws InactiveGymPassException
     */
    public function lockGymPass(NumberOfDays $days): void
    {
        if (!$this->canUsePass()) {
            throw new InactiveGymPassException('Inactive gym pass');
        }

        $this->lockStartDate = (new \DateTimeImmutable())->setTime(0, 0);
        $this->lockEndDate = $this->lockStartDate
            ->add(new DateInterval(sprintf('P%dD', $days->getValue() - 1)));

        $numberOfDaysAreLeft = $this->endDate->diff($this->lockStartDate)->days + 1;

        $this->updateEndDate($numberOfDaysAreLeft);
    }

    private function updateEndDate(int $numberOfDaysAreLeft): void
    {
        $newEndDate = $this->lockEndDate->add(new DateInterval(sprintf('P%sD', $numberOfDaysAreLeft)));
        $this->endDate = $newEndDate;
    }

    public function getLockStartDate(): ?\DateTimeImmutable
    {
        return $this->lockStartDate;
    }

    public function getLockEndDate(): ?\DateTimeImmutable
    {
        return $this->lockEndDate;
    }
}
