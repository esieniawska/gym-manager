<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use ReflectionClass;
use Stringable;

/**
 * @method static Enum fromString(string $value);
 */
abstract class Enum implements Stringable
{
    protected static string $excludedConstraint = 'ALL';

    public function __construct(protected string $value)
    {
        $this->checkIsBetweenAcceptedValues($value);
    }

    abstract protected static function throwExceptionForInvalidValue(string $value): void;

    public static function __callStatic($name, $arguments): Enum
    {
        if ('fromString' === $name) {
            $name = $arguments[0];
        }
        self::checkIsBetweenAcceptedValues($name);

        return new static($name);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function checkIsBetweenAcceptedValues(string $value): void
    {
        if (!in_array($value, self::getValues(), true)) {
            static::throwExceptionForInvalidValue($value);
        }
    }

    private static function getValues(): array
    {
        $class = get_called_class();

        $reflectionClass = new ReflectionClass($class);
        $constants = $reflectionClass->getConstants();
        unset($constants[self::$excludedConstraint]);

        return $constants;
    }

    public function isTheSameType(Enum $other): bool
    {
        return $other->value === $this->value;
    }
}
