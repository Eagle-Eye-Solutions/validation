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
use Respect\Validation\Exceptions\IntValException;
use Respect\Validation\Rules\IntVal;

/**
 * @group  rule
 * @covers IntVal
 * @covers IntValException
 */
class IntValTest extends TestCase
{
    protected IntVal $intValidator;

    protected function setUp(): void
    {
        $this->intValidator = new IntVal();
    }

    /**
     * @dataProvider providerForInt
     */
    public function testValidIntegersShouldReturnTrue($input): void
    {
        static::assertTrue($this->intValidator->__invoke($input));
        static::assertTrue($this->intValidator->check($input));
        static::assertTrue($this->intValidator->assert($input));
    }

    /**
     * @dataProvider providerForNotInt
     *
     */
    public function testInvalidIntegersShouldThrowIntException($input)
    {
        $this->expectException(IntValException::class);
        static::assertFalse($this->intValidator->__invoke($input));
        static::assertFalse($this->intValidator->assert($input));
    }

    public static function providerForInt(): array
    {
        return [
            [16],
            ['165'],
            [123456],
            [PHP_INT_MAX],
        ];
    }

    public static function providerForNotInt(): array
    {
        return [
            [''],
            [null],
            ['a'],
            [' '],
            ['Foo'],
            ['1.44'],
            [1e-5],
        ];
    }
}
