<?php

declare(strict_types=1);

namespace App\Domain\Client\Model;

use App\Domain\Shared\Model\DomainModel;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\EmailAddress;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\PersonalName;
use App\Domain\Shared\ValueObject\Uuid;

class Client extends DomainModel
{
    public function __construct(
        protected Uuid $id,
        private PersonalName $personalName,
        private CardNumber $cardNumber,
        private Gender $gender,
        private ClientStatus $clientStatus,
        private ?EmailAddress $emailAddress,
        private ?PhoneNumber $phoneNumber,
    ) {
        parent::__construct($id);
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

    public function updatePersonalName(PersonalName $personalName): self
    {
        $this->personalName = $personalName;

        return $this;
    }

    public function updateGender(Gender $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function updateClientStatus(ClientStatus $clientStatus): Client
    {
        $this->clientStatus = $clientStatus;

        return $this;
    }

    public function updateEmailAddress(?EmailAddress $emailAddress): Client
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function updatePhoneNumber(?PhoneNumber $phoneNumber): Client
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->clientStatus->isTheSameType(ClientStatus::ACTIVE());
    }
}
