<?php

declare(strict_types=1);

namespace App\UI\GymPass\Http\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'path' => '/gym-passes/lock',
            'status' => 200,
            'output' => 'App\UI\GymPass\Http\Dto\GymPassLockingOutput',
            'denormalization_context' => ['groups' => self::GROUP_WRITE],
            'security' => "is_granted('ROLE_ADMIN')",
            'openapi_context' => [
                'tags' => ['Gym pass'],
                'summary' => 'Locking a gym pass',
            ],
        ],
    ],
    itemOperations: [],
)]
class GymPassLockingForm
{
    public const GROUP_WRITE = __CLASS__.'.write';
    #[ApiProperty(
        description: 'Number of days of freezing the gym pass',
        identifier: true,
        example: 10
    )]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
    #[Groups([self::GROUP_WRITE])]
    public int $numberOfDays;

    #[ApiProperty(
        description: 'Gym pass id',
        example: '62877050-33e6-4d1b-b4e3-245de764aaf8'
    )]
    #[Assert\Uuid]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_WRITE])]
    public string $gymPassId;

    public function __construct(int $numberOfDays, string $gymPassId)
    {
        $this->numberOfDays = $numberOfDays;
        $this->gymPassId = $gymPassId;
    }
}
