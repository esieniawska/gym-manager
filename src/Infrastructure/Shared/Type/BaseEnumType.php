<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Type;

use App\Domain\Shared\ValueObject\Enum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class BaseEnumType extends Type
{
    public const NAME = 'type';

    abstract protected function createFromString(string $value): Enum;

    /**
     * @codeCoverageIgnore
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'VARCHAR(50)';
    }

    /**
     * Converts a value from its PHP representation to its database representation
     * of this type.
     *
     * @param mixed            $value    the value to convert
     * @param AbstractPlatform $platform the currently used database platform
     *
     * @return mixed the database representation of the value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return null !== $value ? (string) $value : null;
    }

    /**
     * Converts a value from its database representation to its PHP representation
     * of this type.
     *
     * @param mixed            $value    the value to convert
     * @param AbstractPlatform $platform the currently used database platform
     *
     * @return mixed the PHP representation of the value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return null !== $value ? $this->createFromString($value) : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return self::NAME;
    }
}
