<?php

declare(strict_types=1);

namespace App\UI\Client\Http\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Client\Entity\ClientStatus;
use App\Domain\Client\Entity\PhoneNumber;
use App\Domain\Shared\Model\Gender;
use App\UI\Shared\Dto\BaseDto;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'path' => '/clients',
            'status' => 201,
            'denormalization_context' => ['groups' => self::GROUP_WRITE],
            'security' => "is_granted('ROLE_ADMIN')",
            'openapi_context' => [
                'tags' => ['Client'],
                'summary' => 'Create client',
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
            'path' => '/clients',
            'normalization_context' => ['groups' => self::GROUP_READ],
            'security' => "is_granted('ROLE_ADMIN')",
            'openapi_context' => [
                'tags' => ['Client'],
                'summary' => 'Get client collection',
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
    ],
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/clients/{id}',
            'normalization_context' => ['groups' => self::GROUP_READ],
            'security' => "is_granted('ROLE_ADMIN')",
            'openapi_context' => [
                'tags' => ['Client'],
                'summary' => 'Get client',
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
                        'description' => 'Client not found.',
                    ],
                ],
            ],
        ],
        'put' => [
            'method' => 'PUT',
            'path' => '/clients/{id}',
            'denormalization_context' => ['groups' => self::GROUP_UPDATE],
            'security' => "is_granted('ROLE_ADMIN')",
            'openapi_context' => [
                'tags' => ['Client'],
                'summary' => 'Update client',
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
                        'description' => 'Client not found.',
                    ],
                ],
            ],
        ],
    ],
)]
class ClientDto implements BaseDto
{
    public const GROUP_WRITE = __CLASS__.'.write';
    public const GROUP_UPDATE = __CLASS__.'.put';
    public const GROUP_READ = __CLASS__.'.read';

    #[ApiProperty(
        description: 'Client ID',
        identifier: true,
        example: '0ecaedac-1bc0-4f4d-8c80-5a5e1cf1c41f'
    )]
    #[Groups([self::GROUP_READ])]
    #[Assert\Uuid]
    private string $id;

    #[ApiProperty(
        description: 'Client card number',
        example: '1234'
    )]
    #[Groups([self::GROUP_READ])]
    private string $cardNumber;

    #[ApiProperty(
        description: 'Client first name',
        example: 'Joe'
    )]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups([self::GROUP_WRITE, self::GROUP_UPDATE, self::GROUP_READ])]
    private string $firstName;

    #[ApiProperty(
        description: 'Client last name',
        example: 'Smith'
    )]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups([self::GROUP_WRITE, self::GROUP_UPDATE, self::GROUP_READ])]
    private string $lastName;

    #[ApiProperty(
        description: 'Client gender',
        example: Gender::MALE,
        openapiContext: [
            'enum' => Gender::GENDERS,
            'type' => 'string',
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Choice(Gender::GENDERS)]
    #[Groups([self::GROUP_WRITE, self::GROUP_UPDATE, self::GROUP_READ])]
    private string $gender;

    #[ApiProperty(
        description: 'Client status',
        example: ClientStatus::ACTIVE,
        openapiContext: [
            'enum' => ClientStatus::STATUSES,
            'type' => 'string',
        ]
    )]
    #[Assert\NotBlank(groups: [self::GROUP_UPDATE])]
    #[Assert\Choice(ClientStatus::STATUSES)]
    #[Groups([self::GROUP_UPDATE, self::GROUP_READ])]
    private string $status;

    #[ApiProperty(
        description: 'Client phone number',
        example: '123456789'
    )]
    #[Assert\Regex(pattern: PhoneNumber::PHONE_PATTERN)]
    #[Groups([self::GROUP_WRITE, self::GROUP_UPDATE, self::GROUP_READ])]
    private ?string $phoneNumber;

    #[ApiProperty(
        description: 'Client email',
        example: 'client@gym.com'
    )]
    #[Assert\Length(max: 180)]
    #[Assert\Email()]
    #[Groups([self::GROUP_WRITE, self::GROUP_UPDATE, self::GROUP_READ])]
    private ?string $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setCardNumber(string $cardNumber): ClientDto
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): ClientDto
    {
        $this->id = $id;

        return $this;
    }

    public function setStatus(string $status): ClientDto
    {
        $this->status = $status;

        return $this;
    }

    public function setFirstName(string $firstName): ClientDto
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): ClientDto
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setGender(string $gender): ClientDto
    {
        $this->gender = $gender;

        return $this;
    }

    public function setPhoneNumber(?string $phoneNumber): ClientDto
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function setEmail(?string $email): ClientDto
    {
        $this->email = $email;

        return $this;
    }
}
