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
use Respect\Validation\Exceptions\LowercaseException;
use Respect\Validation\Rules\Lowercase;

/**
 * @group  rule
 * @covers Lowercase
 * @covers LowercaseException
 */
class LowercaseTest extends TestCase
{
    /**
     * @dataProvider providerForValidLowercase
     * @throws \Exception
     */
    public function testValidLowercaseShouldReturnTrue($input): void
    {
        $lowercase = new Lowercase();
        static::assertTrue($lowercase->__invoke($input));
        static::assertTrue($lowercase->assert($input));
        static::assertTrue($lowercase->check($input));
    }

    /**
     * @dataProvider providerForInvalidLowercase
     *
     * @throws \Exception
     */
    public function testInvalidLowercaseShouldThrowException($input): void
    {
        $this->expectException(LowercaseException::class);
        $lowercase = new Lowercase();
        static::assertFalse($lowercase->__invoke($input));
        static::assertFalse($lowercase->assert($input));
    }

    public static function providerForValidLowercase(): array
    {
        return [
            [''],
            ['lowercase'],
            ['lowercase-with-dashes'],
            ['lowercase with spaces'],
            ['lowercase with numbers 123'],
            ['lowercase with specials characters like ã ç ê'],
            ['with specials characters like # $ % & * +'],
            ['τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός'],
        ];
    }

    public static function providerForInvalidLowercase(): array
    {
        return [
            ['UPPERCASE'],
            ['CamelCase'],
            ['First Character Uppercase'],
            ['With Numbers 1 2 3'],
        ];
    }
}
