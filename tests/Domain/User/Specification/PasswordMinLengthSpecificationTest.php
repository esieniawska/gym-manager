<?php

namespace App\Tests\Domain\User\Specification;

use App\Domain\User\Specification\PasswordMinLengthSpecification;
use PHPUnit\Framework\TestCase;

class PasswordMinLengthSpecificationTest extends TestCase
{
    public function testIsSatisfiedBy(): void
    {
        $specification = new PasswordMinLengthSpecification(5);
        $this->assertTrue($specification->isSatisfiedBy('test1'));
        $this->assertFalse($specification->isSatisfiedBy('test'));
    }
}
