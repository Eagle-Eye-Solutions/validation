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
use Respect\Validation\Exceptions\NoException;
use Respect\Validation\Rules\No;

#[CoversClass(No::class)]
#[CoversClass(NoException::class)]
#[Group('rule')]
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

    #[DataProvider('validNoProvider')]
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

    #[DataProvider('invalidNoProvider')]
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
