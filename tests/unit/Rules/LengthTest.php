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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\LengthException;
use Respect\Validation\Rules\Length;

#[CoversClass(Length::class)]
#[CoversClass(LengthException::class)]
#[Group('rule')]
class LengthTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForValidLengthInclusive')]
    public function testLengthInsideBoundsForInclusiveCasesReturnTrue($string, $min, $max): void
    {
        $validator = new Length($min, $max, true);
        static::assertTrue($validator->validate($string));
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForValidLengthNonInclusive')]
    public function testLengthInsideBoundsForNonInclusiveCasesShouldReturnTrue($string, $min, $max): void
    {
        $validator = new Length($min, $max, false);
        static::assertTrue($validator->validate($string));
    }

    #[DataProvider('providerForInvalidLengthInclusive')]
    public function testLengthOutsideBoundsForInclusiveCasesReturnFalse($string, $min, $max): void
    {
        $validator = new Length($min, $max, true);
        static::assertFalse($validator->validate($string));
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForInvalidLengthNonInclusive')]
    public function testLengthOutsideBoundsForNonInclusiveCasesReturnFalse($string, $min, $max)
    {
        $validator = new Length($min, $max, false);
        static::assertFalse($validator->validate($string));
    }

    #[DataProvider('providerForComponentException')]
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
