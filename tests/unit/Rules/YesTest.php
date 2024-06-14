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
use Respect\Validation\Rules\Yes;

/**
 * @group  rule
 * @covers Yes
 * @covers YesException
 */
class YesTest extends TestCase
{
    public function testShouldUseDefaultPattern(): void
    {
        $rule = new Yes();

        $actualPattern = $rule->regex;
        $expectedPattern = '/^y(eah?|ep|es)?$/i';

        static::assertSame($expectedPattern, $actualPattern);
    }

    public function testShouldUseLocalPatternForYesExpressionWhenDefined(): void
    {
        if (!defined('YESEXPR')) {
            static::markTestSkipped('Constant YESEXPR is not defined');
        }

        $rule = new Yes(true);

        $actualPattern = $rule->regex;
        $expectedPattern = '/'.nl_langinfo(YESEXPR).'/i';

        static::assertSame($expectedPattern, $actualPattern);
    }

    /**
     * @dataProvider validYesProvider
     */
    public function testShouldValidatePatternAccordingToTheDefinedLocale($input): void
    {
        $rule = new Yes();

        static::assertTrue($rule->validate($input));
    }

    public static function validYesProvider(): array
    {
        return [
            ['Y'],
            ['Yea'],
            ['Yeah'],
            ['Yep'],
            ['Yes'],
        ];
    }

    /**
     * @dataProvider invalidYesProvider
     */
    public function testShouldNotValidatePatternAccordingToTheDefinedLocale($input): void
    {
        $rule = new Yes();

        static::assertFalse($rule->validate($input));
    }

    public static function invalidYesProvider(): array
    {
        return [
            ['Si'],
            ['Sim'],
            ['Yoo'],
            ['Young'],
            ['Yy'],
        ];
    }
}
