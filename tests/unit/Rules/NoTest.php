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
use Respect\Validation\Rules\No;

/**
 * @group  rule
 * @covers No
 * @covers NoException
 */
class NoTest extends TestCase
{
    public function testShouldUseDefaultPattern()
    {
        $rule = new No();

        $actualPattern = $rule->regex;
        $expectedPattern = '/^n(o(t|pe)?|ix|ay)?$/i';

        static::assertSame($expectedPattern, $actualPattern);
    }

    public function testShouldUseLocalPatternForNoExpressionWhenDefined(): void
    {
        if (!defined('NOEXPR')) {
            $this->markTestSkipped('Constant NOEXPR is not defined');
        }

        $rule = new No(true);

        $actualPattern = $rule->regex;
        $expectedPattern = '/'.nl_langinfo(NOEXPR).'/i';

        static::assertSame($expectedPattern, $actualPattern);
    }

    /**
     * @dataProvider validNoProvider
     */
    public function testShouldValidatePatternAccordingToTheDefinedLocale($input): void
    {
        $rule = new No();

        static::assertTrue($rule->validate($input));
    }

    public static function validNoProvider(): array
    {
        return [
            ['N'],
            ['Nay'],
            ['Nix'],
            ['No'],
            ['Nope'],
            ['Not'],
        ];
    }

    /**
     * @dataProvider invalidNoProvider
     */
    public function testShouldNotValidatePatternAccordingToTheDefinedLocale($input): void
    {
        $rule = new No();

        static::assertFalse($rule->validate($input));
    }

    public static function invalidNoProvider(): array
    {
        return [
            ['Donnot'],
            ['Never'],
            ['Niet'],
            ['Noooooooo'],
            ['Não'],
        ];
    }
}
