<?php

namespace App\Tests\Application\Offer\Factory;

use App\Application\Offer\Dto\CreateNumberOfDaysOfferDto;
use App\Application\Offer\Dto\CreateNumberOfEntriesOfferDto;
use App\Application\Offer\Dto\CreateOfferDto;
use App\Application\Offer\Exception\InvalidOfferTypeException;
use App\Application\Offer\Factory\CreateOfferFactory;
use App\Domain\Offer\Model\TicketOfferWithNumberOfDays;
use App\Domain\Offer\Model\TicketOfferWithNumberOfEntries;
use App\Domain\Offer\Repository\OfferRepository;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreateOfferWithoutGenderFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|OfferRepository $repositoryMock;
    private CreateOfferFactory $factory;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->prophesize(OfferRepository::class);
        $this->factory = new CreateOfferFactory($this->repositoryMock->reveal());
    }

    public function testCreateTicketOfferWithNumberOfEntries(): void
    {
        $this->repositoryMock->nextIdentity()->willReturn(new Uuid('ce871a0b-567d-475d-ac7e-33210e314152'));
        $dto = new CreateNumberOfEntriesOfferDto('new-offer', 1, 1);
        $result = $this->factory->createOfferTicket($dto);
        $this->assertInstanceOf(TicketOfferWithNumberOfEntries::class, $result);
    }

    public function testCreateTicketOfferWithNumberOfDays(): void
    {
        $this->repositoryMock->nextIdentity()->willReturn(new Uuid('ce871a0b-567d-475d-ac7e-33210e314152'));
        $dto = new CreateNumberOfDaysOfferDto('new-offer', 1, 1);
        $result = $this->factory->createOfferTicket($dto);
        $this->assertInstanceOf(TicketOfferWithNumberOfDays::class, $result);
    }

    public function testTryCreateOfferWithInvalidType(): void
    {
        $this->repositoryMock->nextIdentity()->willReturn(new Uuid('ce871a0b-567d-475d-ac7e-33210e314152'));
        $dto = $this->prophesize(CreateOfferDto::class);
        $this->expectException(InvalidOfferTypeException::class);
        $this->factory->createOfferTicket($dto->reveal());
    }
}
