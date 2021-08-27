<?php

namespace App\Tests\Domain\User\Specification;

use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Specification\EmailIsUniqueSpecification;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class EmailIsUniqueSpecificationTest extends TestCase
{
    use ProphecyTrait;

    private UserRepository|ObjectProphecy $repositoryMock;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->prophesize(UserRepository::class);
    }

    public function testIsSatisfiedByWhenUserNotFound(): void
    {
        $this->repositoryMock->getByEmail('joe@exaple.com')->willReturn(null);
        $specification = new EmailIsUniqueSpecification($this->repositoryMock->reveal());
        $this->assertTrue($specification->isSatisfiedBy('joe@exaple.com'));
    }

    public function testIsSatisfiedByWhenUserExist(): void
    {
        $userMock = $this->prophesize(User::class);
        $this->repositoryMock->getByEmail('joe@exaple.com')->willReturn($userMock->reveal());
        $specification = new EmailIsUniqueSpecification($this->repositoryMock->reveal());
        $this->assertFalse($specification->isSatisfiedBy('joe@exaple.com'));
    }
}
