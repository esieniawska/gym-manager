<?php

declare(strict_types=1);

namespace App\UI\GymPass\Http\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class GymPassLockingOutput
{
    #[ApiProperty(
        description: 'New end date',
        example: '2021-05-30'
    )]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    public \DateTimeImmutable $endDate;

    #[ApiProperty(
        description: 'Lock start date',
        example: '2021-05-21'
    )]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    public \DateTimeImmutable $lockStartDate;

    #[ApiProperty(
        description: 'Lock end date',
        example: '2021-05-25'
    )]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    public \DateTimeImmutable $lockEndDate;

    public function __construct(\DateTimeImmutable $endDate, \DateTimeImmutable $lockStartDate, \DateTimeImmutable $lockEndDate)
    {
        $this->endDate = $endDate;
        $this->lockStartDate = $lockStartDate;
        $this->lockEndDate = $lockEndDate;
    }
}
