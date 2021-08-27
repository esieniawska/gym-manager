<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\Entity;

use App\Infrastructure\Shared\Entity\DbEntity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Model(repositoryClass="App\Infrastructure\Client\Repository\DoctrineClientRepository")
 * @ORM\Table(name="client")
 * @codeCoverageIgnore
 */
class DbClient implements DbEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $cardNumber;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $gender;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $status;
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private ?string $phoneNumber;

    public function __construct(
        UuidInterface $id,
        string $firstName,
        string $lastName,
        string $cardNumber,
        string $gender,
        string $status,
        ?string $email,
        ?string $phoneNumber
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->cardNumber = $cardNumber;
        $this->gender = $gender;
        $this->status = $status;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setFirstName(string $firstName): DbClient
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): DbClient
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setGender(string $gender): DbClient
    {
        $this->gender = $gender;

        return $this;
    }

    public function setStatus(string $status): DbClient
    {
        $this->status = $status;

        return $this;
    }

    public function setEmail(?string $email): DbClient
    {
        $this->email = $email;

        return $this;
    }

    public function setPhoneNumber(?string $phoneNumber): DbClient
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
