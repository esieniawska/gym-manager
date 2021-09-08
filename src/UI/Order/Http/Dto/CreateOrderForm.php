<?php

declare(strict_types=1);

namespace App\UI\Order\Http\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Shared\ValueObject\CardNumber;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'path' => '/orders',
            'status' => 204,
            'output' => false,
            'denormalization_context' => ['groups' => self::GROUP_WRITE],
            'security' => "is_granted('ROLE_ADMIN')",
            'openapi_context' => [
                'tags' => ['Order'],
                'summary' => 'Create order',
            ],
        ],
    ],
    itemOperations: [],
)]
class CreateOrderForm
{
    public const GROUP_WRITE = __CLASS__.'.write';
    #[ApiProperty(
        description: 'Client card number',
        identifier: true,
        example: '9b045fee101aa548c276fe5f7f907799'
    )]
    #[Assert\Regex(pattern: CardNumber::NUMBER_PATTERN)]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_WRITE])]
    public string $cardNumber;

    #[ApiProperty(
        description: 'Offer id',
        example: '62877050-33e6-4d1b-b4e3-245de764aaf8'
    )]
    #[Assert\Uuid]
    #[Assert\NotBlank]
    #[Groups([self::GROUP_WRITE])]
    public string $offerId;

    #[ApiProperty(
        description: 'Start date',
        example: '2021-05-21'
    )]
    #[Assert\GreaterThanOrEqual(value: 'today')]
    #[Assert\NotBlank]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Groups([self::GROUP_WRITE])]
    public \DateTimeImmutable $startDate;

    public function __construct(string $cardNumber, string $offerId, \DateTimeImmutable $startDate)
    {
        $this->cardNumber = $cardNumber;
        $this->offerId = $offerId;
        $this->startDate = $startDate;
    }
}
