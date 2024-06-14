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
use Respect\Validation\Rules\Contains;

/**
 * @group  rule
 * @covers Contains
 * @covers ContainsException
 */
class ContainsTest extends TestCase
{
    /**
     * @dataProvider providerForContainsIdentical
     */
    public function testStringsContainingExpectedIdenticalValueShouldPass($start, $input)
    {
        $v = new Contains($start, true);
        static::assertTrue($v->validate($input));
    }

    /**
     * @dataProvider providerForContains
     */
    public function testStringsContainingExpectedValueShouldPass($start, $input)
    {
        $v = new Contains($start, false);
        static::assertTrue($v->validate($input));
    }

    /**
     * @dataProvider providerForNotContainsIdentical
     */
    public function testStringsNotContainsExpectedIdenticalValueShouldNotPass($start, $input)
    {
        $v = new Contains($start, true);
        static::assertFalse($v->validate($input));
    }

    /**
     * @dataProvider providerForNotContains
     */
    public function testStringsNotContainsExpectedValueShouldNotPass($start, $input)
    {
        $v = new Contains($start, false);
        static::assertFalse($v->validate($input));
    }

    public static function providerForContains()
    {
        return [
            ['foo', ['bar', 'foo']],
            ['foo', 'barbazFOO'],
            ['foo', 'barbazfoo'],
            ['foo', 'foobazfoO'],
            ['1', [2, 3, 1]],
            ['1', [2, 3, '1']],
        ];
    }

    public static function providerForContainsIdentical()
    {
        return [
            ['foo', ['fool', 'foo']],
            ['foo', 'barbazfoo'],
            ['foo', 'foobazfoo'],
            ['1', [2, 3, (string) 1]],
            ['1', [2, 3, '1']],
        ];
    }

    public static function providerForNotContains()
    {
        return [
            ['foo', ''],
            ['bat', ['bar', 'foo']],
            ['foo', 'barfaabaz'],
            ['foo', 'faabarbaz'],
        ];
    }

    public static function providerForNotContainsIdentical()
    {
        return [
            ['foo', ''],
            ['bat', ['BAT', 'foo']],
            ['bat', ['BaT', 'Batata']],
            ['foo', 'barfaabaz'],
            ['foo', 'barbazFOO'],
            ['foo', 'faabarbaz'],
        ];
    }
}
