<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Client\Model\ClientStatus;
use App\Domain\Offer\Model\OfferStatus;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Money;
use App\UI\Shared\Dto\BaseDto;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'path' => '/offers',
            'status' => 201,
            'denormalization_context' => ['groups' => self::GROUP_WRITE],
            'security' => "is_granted('ROLE_ADMIN')",
            'openapi_context' => [
                'tags' => ['Offer'],
                'summary' => 'Create offer',
                'responses' => [
                    '400' => [
                        'description' => 'Invalid input.',
                    ],
                    '401' => [
                        'description' => 'Missing authentication parameters.',
                    ],
                    '403' => [
                        'description' => 'Access Denied.',
                    ],
                ],
            ],
        ],
        'get' => [
            'method' => 'GET',
            'path' => '/offers',
            'normalization_context' => ['groups' => self::GROUP_READ],
            'security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
            'openapi_context' => [
                'tags' => ['Offer'],
                'summary' => 'Get offer collection',
                'responses' => [
                    '401' => [
                        'description' => 'Missing authentication parameters.',
                    ],
                    '403' => [
                        'description' => 'Access Denied.',
                    ],
                ],
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/offers/{id}',
            'normalization_context' => ['groups' => self::GROUP_READ],
            'security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
            'openapi_context' => [
                'tags' => ['Offer'],
                'summary' => 'Get offer',
                'responses' => [
                    '400' => [
                        'description' => 'Invalid input.',
                    ],
                    '401' => [
                        'description' => 'Missing authentication parameters.',
                    ],
                    '403' => [
                        'description' => 'Access Denied.',
                    ],
                    '404' => [
                        'description' => 'Offer not found.',
                    ],
                ],
            ],
        ],
    ],
)]
class OfferDto implements BaseDto
{
    public const GROUP_WRITE = __CLASS__.'.write';
    public const GROUP_UPDATE = __CLASS__.'.put';
    public const GROUP_READ = __CLASS__.'.read';

    #[ApiProperty(
        description: 'Offer ID',
        identifier: true,
        example: '0ecaedac-1bc0-4f4d-8c80-5a5e1cf1c41f'
    )]
    #[Groups([self::GROUP_READ])]
    #[Assert\Uuid]
    private string $id;

    #[ApiProperty(
        description: 'Offer name',
        example: 'Special offer'
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[Groups([self::GROUP_WRITE, self::GROUP_UPDATE, self::GROUP_READ])]
    private string $name;

    #[ApiProperty(
        description: 'Price',
        example: 58.99
    )]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\Regex(pattern: Money::PATTERN)]
    #[Groups([self::GROUP_WRITE, self::GROUP_UPDATE, self::GROUP_READ])]
    private float $price;

    #[ApiProperty(
        description: 'Quantity',
        example: 10
    )]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups([self::GROUP_WRITE, self::GROUP_UPDATE, self::GROUP_READ])]
    private int $quantity;

    #[ApiProperty(
        description: 'Offer only for one gender',
        example: Gender::MALE,
        openapiContext: [
            'enum' => Gender::ALL,
            'type' => 'string',
        ]
    )]
    #[Assert\Choice(Gender::ALL)]
    #[Groups([self::GROUP_WRITE, self::GROUP_READ])]
    private ?string $gender = null;

    #[ApiProperty(
        description: 'Offer status',
        example: OfferStatus::ACTIVE,
        openapiContext: [
            'enum' => OfferStatus::ALL,
            'type' => 'string',
        ]
    )]
    #[Assert\NotBlank(groups: [self::GROUP_UPDATE])]
    #[Assert\Choice(ClientStatus::ALL)]
    #[Groups([self::GROUP_UPDATE, self::GROUP_READ])]
    private string $status;

    #[ApiProperty(
        description: 'Offer type',
        example: OfferType::TYPE_NUMBER_OF_DAYS,
        openapiContext: [
            'enum' => OfferType::ALL,
            'type' => 'string',
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Choice(OfferType::ALL)]
    #[Groups([self::GROUP_WRITE, self::GROUP_READ])]
    private string $type;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): OfferDto
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): OfferDto
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): OfferDto
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): OfferDto
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): OfferDto
    {
        $this->gender = $gender;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): OfferDto
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): OfferDto
    {
        $this->type = $type;

        return $this;
    }
}
