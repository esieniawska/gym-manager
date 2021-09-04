<?php

namespace App\Tests\Application\Offer\Factory;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\CreateNumberOfEntriesOfferDto;
use App\Application\Offer\Factory\CreateOfferWithGenderFactory;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDaysAndGender;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntriesAndGender;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Gender;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateOfferWithGenderFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferRepository $repositoryMock;
    private CreateOfferWithGenderFactory $factory;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->prophesize(OfferRepository::class);
        $this->factory = new CreateOfferWithGenderFactory($this->repositoryMock->reveal());
    }

    public function testCreateTicketOfferWithNumberOfEntries(): void
    {
        $this->repositoryMock->nextIdentity()->willReturn(new Uuid('ce871a0b-567d-475d-ac7e-33210e314152'));
        $dto = new CreateNumberOfEntriesOfferDto('new-offer', 1, 1, Gender::FEMALE);
        $result = $this->factory->createOfferTicket($dto);
        $this->assertInstanceOf(TicketOfferWithNumberOfEntriesAndGender::class, $result);
    }

    public function testCreateTicketOfferWithNumberOfDays(): void
    {
        $this->repositoryMock->nextIdentity()->willReturn(new Uuid('ce871a0b-567d-475d-ac7e-33210e314152'));
        $dto = new CreateNumberOfDaysOfferDto('new-offer', 1, 1, Gender::FEMALE);
        $result = $this->factory->createOfferTicket($dto);
        $this->assertInstanceOf(TicketOfferWithNumberOfDaysAndGender::class, $result);
    }
}
