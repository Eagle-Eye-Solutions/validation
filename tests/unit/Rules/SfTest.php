<?php

namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\SfException;
use Respect\Validation\Rules\Sf;

#[CoversClass(Sf::class)]
#[CoversClass(SfException::class)]
#[Group('rule')]
class SfTest extends TestCase
{
    public function testDefinedConstraintAndValidatorWithNull(): void
    {
        $sut = new Sf('Time');

        self::assertTrue($sut->validate(null));
    }

    public function testDefinedConstraintAndValidatorWithTrue(): void
    {
        $sut = new Sf('Time');

        self::assertFalse($sut->validate(true));
    }

    public function testDefinedConstraintAndValidatorWithInvalid(): void
    {
        $sut = new Sf('Time');

        self::assertFalse($sut->validate('yada'));
    }

    public function testDefinedConstraintAndValidatorWithValid(): void
    {
        $sut = new Sf('Time');

        self::assertTrue($sut->validate('04:20:00'));
    }
}
