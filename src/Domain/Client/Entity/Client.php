<?php

declare(strict_types=1);

namespace App\Domain\Client\Entity;

use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\Model\EmailAddress;
use App\Domain\Shared\Model\Gender;
use App\Domain\Shared\Model\PersonalName;
use App\Domain\Shared\Model\Uuid;

class Client implements DomainModel
{
    public function __construct(
        private Uuid $id,
        private PersonalName $personalName,
        private CardNumber $cardNumber,
        private Gender $gender,
        private ClientStatus $clientStatus,
        private ?EmailAddress $emailAddress,
        private ?PhoneNumber $phoneNumber,
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPersonalName(): PersonalName
    {
        return $this->personalName;
    }

    public function getCardNumber(): CardNumber
    {
        return $this->cardNumber;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function getClientStatus(): ClientStatus
    {
        return $this->clientStatus;
    }

    public function getEmailAddress(): ?EmailAddress
    {
        return $this->emailAddress;
    }

    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function setPersonalName(PersonalName $personalName): self
    {
        $this->personalName = $personalName;

        return $this;
    }

    public function setGender(Gender $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function setClientStatus(ClientStatus $clientStatus): Client
    {
        $this->clientStatus = $clientStatus;

        return $this;
    }

    public function setEmailAddress(?EmailAddress $emailAddress): Client
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function setPhoneNumber(?PhoneNumber $phoneNumber): Client
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
