<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Length;

/**
 * @group  rule
 * @covers Length
 * @covers LengthException
 */
class LengthTest extends TestCase
{
    /**
     * @dataProvider providerForValidLengthInclusive
     * @throws ComponentException
     */
    public function testLengthInsideBoundsForInclusiveCasesReturnTrue($string, $min, $max): void
    {
        $validator = new Length($min, $max, true);
        static::assertTrue($validator->validate($string));
    }

    /**
     * @dataProvider providerForValidLengthNonInclusive
     * @throws ComponentException
     */
    public function testLengthInsideBoundsForNonInclusiveCasesShouldReturnTrue($string, $min, $max): void
    {
        $validator = new Length($min, $max, false);
        static::assertTrue($validator->validate($string));
    }

    /**
     * @dataProvider providerForInvalidLengthInclusive
     */
    public function testLengthOutsideBoundsForInclusiveCasesReturnFalse($string, $min, $max): void
    {
        $validator = new Length($min, $max, true);
        static::assertFalse($validator->validate($string));
    }

    /**
     * @dataProvider providerForInvalidLengthNonInclusive
     * @throws ComponentException
     */
    public function testLengthOutsideBoundsForNonInclusiveCasesReturnFalse($string, $min, $max)
    {
        $validator = new Length($min, $max, false);
        static::assertFalse($validator->validate($string));
    }

    /**
     * @dataProvider providerForComponentException
     */
    public function testComponentExceptionsForInvalidParameters($min, $max): void
    {
        $this->expectException(ComponentException::class);
        new Length($min, $max);
    }

    public static function providerForValidLengthInclusive(): array
    {
        return [
            ['alganet', 1, 15],
            ['ççççç', 4, 6],
            [range(1, 20), 1, 30],
            [(object) ['foo' => 'bar', 'bar' => 'baz'], 0, 2],
            ['alganet', 1, null], //null is a valid max length, means "no maximum",
            ['alganet', null, 15], //null is a valid min length, means "no minimum"
        ];
    }

    public static function providerForValidLengthNonInclusive(): array
    {
        return [
            ['alganet', 1, 15],
            ['ççççç', 4, 6],
            [range(1, 20), 1, 30],
            [(object) ['foo' => 'bar', 'bar' => 'baz'], 1, 3],
            ['alganet', 1, null], //null is a valid max length, means "no maximum",
            ['alganet', null, 15], //null is a valid min length, means "no minimum"
        ];
    }

    public static function providerForInvalidLengthInclusive(): array
    {
        return [
            ['', 1, 15],
            ['alganet', 1, 6],
            [range(1, 20), 1, 19],
            ['alganet', 8, null], //null is a valid max length, means "no maximum",
            ['alganet', null, 6], //null is a valid min length, means "no minimum"
        ];
    }

    public static function providerForInvalidLengthNonInclusive(): array
    {
        return [
            ['alganet', 1, 7],
            [(object) ['foo' => 'bar', 'bar' => 'baz'], 3, 5],
            [range(1, 50), 1, 30],
        ];
    }

    public static function providerForComponentException(): array
    {
        return [
            ['a', 15],
            [1, 'abc d'],
            [10, 1],
        ];
    }
}
