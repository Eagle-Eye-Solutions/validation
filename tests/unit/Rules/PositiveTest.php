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
use Respect\Validation\Exceptions\PositiveException;
use Respect\Validation\Rules\Positive;

/**
 * @group  rule
 * @covers Positive
 * @covers PositiveException
 */
class PositiveTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Positive();
    }

    /**
     * @dataProvider providerForPositive
     */
    public function testPositive($input): void
    {
        static::assertTrue($this->object->__invoke($input));
        static::assertTrue($this->object->check($input));
        static::assertTrue($this->object->assert($input));
    }

    /**
     * @dataProvider providerForNotPositive
     * @throws \Exception
     */
    public function testNotPositive($input): void
    {
        $this->expectException(PositiveException::class);
        static::assertFalse($this->object->__invoke($input));
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForPositive(): array
    {
        return [
            [16],
            ['165'],
            [123456],
            [1e10],
            ['a'],
            ['Foo'],
        ];
    }

    public static function providerForNotPositive(): array
    {
        return [
            [''],
            [null],
            [' '],
            ['-1.44'],
            [-1e-5],
            [0],
            [-0],
            [-10],
        ];
    }
}
