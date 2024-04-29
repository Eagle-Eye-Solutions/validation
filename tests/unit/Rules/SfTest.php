<?php

namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Rules\Sf;

/**
 * @group  rule
 * @covers Sf
 * @covers SfException
 */
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
