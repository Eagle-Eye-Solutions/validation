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
use Respect\Validation\Rules\TrueVal;

/**
 * @group  rule
 * @covers TrueVal
 * @covers TrueValException
 */
class TrueValTest extends TestCase
{
    /**
     * @dataProvider validTrueProvider
     */
    public function testShouldValidatePatternAccordingToTheDefinedLocale($input)
    {
        $rule = new TrueVal();

        static::assertTrue($rule->validate($input));
    }

    public static function validTrueProvider()
    {
        return [
            [true],
            [1],
            ['1'],
            ['true'],
            ['on'],
            ['yes'],
            ['TRUE'],
            ['ON'],
            ['YES'],
            ['True'],
            ['On'],
            ['Yes'],
        ];
    }

    /**
     * @dataProvider invalidTrueProvider
     */
    public function testShouldNotValidatePatternAccordingToTheDefinedLocale($input)
    {
        $rule = new TrueVal();

        static::assertFalse($rule->validate($input));
    }

    public static function invalidTrueProvider()
    {
        return [
            [false],
            [0],
            [0.5],
            [2],
            ['0'],
            ['false'],
            ['off'],
            ['no'],
            ['truth'],
        ];
    }
}
