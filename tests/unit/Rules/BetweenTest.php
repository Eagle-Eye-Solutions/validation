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

use DateTime;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\BetweenException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Between;

/**
 * @group  rule
 * @covers Between
 * @covers BetweenException
 */
class BetweenTest extends TestCase
{
    public static function providerValid()
    {
        return [
            [0, 1, true, 0],
            [0, 1, true, 1],
            [10, 20, false, 15],
            [10, 20, true, 20],
            [-10, 20, false, -5],
            [-10, 20, false, 0],
            ['a', 'z', false, 'j'],
            [
                new DateTime('yesterday'),
                new DateTime('tomorrow'),
                false,
                new DateTime('now'),
            ],
        ];
    }

    public static function providerInvalid()
    {
        return [
            [10, 20, false, ''],
            [10, 20, true, ''],
            [0, 1, false, 0],
            [0, 1, false, 1],
            [0, 1, false, 2],
            [0, 1, false, -1],
            [10, 20, false, 999],
            [10, 20, false, 20],
            [-10, 20, false, -11],
            ['a', 'j', false, 'z'],
            [
                new DateTime('yesterday'),
                new DateTime('now'),
                false,
                new DateTime('tomorrow'),
            ],
        ];
    }

    /**
     * @dataProvider providerValid
     */
    public function testValuesBetweenBoundsShouldPass($min, $max, $inclusive, $input)
    {
        $o = new Between($min, $max, $inclusive);
        static::assertTrue($o->__invoke($input));
        static::assertTrue($o->assert($input));
        static::assertTrue($o->check($input));
    }

    /**
     * @dataProvider providerInvalid
     */
    public function testValuesOutBoundsShouldRaiseException($min, $max, $inclusive, $input)
    {
        $this->expectException(BetweenException::class);
        $o = new Between($min, $max, $inclusive);
        static::assertFalse($o->__invoke($input));
        static::assertFalse($o->assert($input));
    }

    public function testInvalidConstructionParamsShouldRaiseException(): void
    {
        $this->expectException(ComponentException::class);
        new Between(10, 5);
    }
}
