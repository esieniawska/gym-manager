<?php

namespace App\Tests\Domain\Shared\Specification;

use App\Domain\Shared\Specification\IntMinimumValueSpecification;
use PHPUnit\Framework\TestCase;

class IntMinimumValueSpecificationTest extends TestCase
{
    public function testIsSatisfiedBy()
    {
        $specification = new IntMinimumValueSpecification(2);
        $this->assertFalse($specification->isSatisfiedBy(-1));
        $this->assertFalse($specification->isSatisfiedBy(1));
        $this->assertTrue($specification->isSatisfiedBy(2));
        $this->assertTrue($specification->isSatisfiedBy(3));
        $this->assertFalse($specification->isSatisfiedBy(3.2));
        $this->assertFalse($specification->isSatisfiedBy('test'));
    }
}
