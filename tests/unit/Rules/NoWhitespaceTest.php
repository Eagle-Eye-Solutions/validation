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
use Respect\Validation\Exceptions\NoWhitespaceException as NoWhitespaceExceptionAlias;
use Respect\Validation\Rules\NoWhitespace;

/**
 * @group  rule
 * @covers NoWhitespace
 * @covers NoWhitespaceException
 */
class NoWhitespaceTest extends TestCase
{
    protected $noWhitespaceValidator;

    protected function setUp(): void
    {
        $this->noWhitespaceValidator = new NoWhitespace();
    }

    /**
     * @dataProvider providerForPass
     */
    public function testStringWithNoWhitespaceShouldPass($input): void
    {
        static::assertTrue($this->noWhitespaceValidator->__invoke($input));
        static::assertTrue($this->noWhitespaceValidator->check($input));
        static::assertTrue($this->noWhitespaceValidator->assert($input));
    }

    /**
     * @dataProvider providerForFail
     */
    public function testStringWithWhitespaceShouldFail($input): void
    {
        $this->expectException(NoWhitespaceExceptionAlias::class);
        static::assertFalse($this->noWhitespaceValidator->__invoke($input));
        static::assertFalse($this->noWhitespaceValidator->assert($input));
    }

    public function testStringWithLineBreaksShouldFail(): void
    {
        $this->expectException(NoWhitespaceExceptionAlias::class);
        static::assertFalse($this->noWhitespaceValidator->__invoke("w\npoiur"));
        static::assertFalse($this->noWhitespaceValidator->assert("w\npoiur"));
    }

    public static function providerForPass(): array
    {
        return [
            [''],
            [null],
            [0],
            ['wpoiur'],
            ['Foo'],
        ];
    }

    public static function providerForFail()
    {
        return [
            [' '],
            ['w poiur'],
            ['      '],
            ["Foo\nBar"],
            ["Foo\tBar"],
        ];
    }

    public function testArrayDoesNotThrowAWarning(): void
    {
        $this->expectException(NoWhitespaceExceptionAlias::class);
        $this->noWhitespaceValidator->assert([]);
    }
}
