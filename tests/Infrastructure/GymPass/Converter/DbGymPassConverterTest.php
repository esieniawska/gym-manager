<?php

namespace App\Tests\Infrastructure\GymPass\Converter;

use App\Domain\GymPass\Model\Client;
use App\Domain\GymPass\Model\GymPass;
use App\Domain\GymPass\Model\GymPassWithEndDate;
use App\Domain\GymPass\Model\GymPassWithNumberOfEntries;
use App\Domain\Shared\ValueObject\CardNumber;
use App\Domain\Shared\ValueObject\NumberOfEntries;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\GymPass\Converter\DbGymPassConverter;
use App\Infrastructure\GymPass\Entity\DbGymPassWithEndDate;
use App\Infrastructure\GymPass\Entity\DbGymPassWithNumberOfEntries;
use App\Infrastructure\GymPass\Exception\InvalidGymPassTypeException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DbGymPassConverterTest extends TestCase
{
    use ProphecyTrait;

    public function testConvertDomainObjectWithNumberOfEntriesToDbModel(): void
    {
        $gymPass = new GymPassWithNumberOfEntries(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Client(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            new \DateTimeImmutable(),
            new NumberOfEntries(3)
        );
        $converter = new DbGymPassConverter();

        $result = $converter->convertDomainObjectToDbModel($gymPass);
        $this->assertInstanceOf(DbGymPassWithNumberOfEntries::class, $result);
    }

    public function testConvertDomainObjectWithEndDateToDbModel(): void
    {
        $gymPass = new GymPassWithEndDate(
            new Uuid('7d24cece-b0c6-4657-95d5-31180ebfc8e1'),
            new Client(new CardNumber('caabacb3554c96008ba346a61d1839fa')),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
        $converter = new DbGymPassConverter();

        $result = $converter->convertDomainObjectToDbModel($gymPass);
        $this->assertInstanceOf(DbGymPassWithEndDate::class, $result);
    }

    public function testConvertDomainObjectToDbModelWhenInvalidType(): void
    {
        $gymPass = $this->prophesize(GymPass::class);
        $converter = new DbGymPassConverter();
        $this->expectException(InvalidGymPassTypeException::class);
        $converter->convertDomainObjectToDbModel($gymPass->reveal());
    }
}
