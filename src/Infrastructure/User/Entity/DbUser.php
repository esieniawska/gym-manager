<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Entity;

use App\Domain\User\Model\Roles;
use App\Infrastructure\Shared\Entity\DbEntity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Model(repositoryClass="App\Infrastructure\User\Repository\DoctrineUserRepository")
 * @ORM\Table(name="`user`")
 * @codeCoverageIgnore
 */
class DbUser implements UserInterface, DbEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];
    /**
     * @ORM\Column(type="string")
     */
    private string $passwordHash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    public function __construct(UuidInterface $id, string $email, string $passwordHash, string $firstName, string $lastName, array $roles)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->roles = $roles;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = Roles::ROLE_USER;

        return array_unique($roles);
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function getEmail(): string
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

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        return (string) $this->email;
    }

    public function getUserIdentifier()
    {
        return $this->getId();
    }
}
