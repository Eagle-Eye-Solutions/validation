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
use Respect\Validation\Exceptions\PerfectSquareException;
use Respect\Validation\Rules\PerfectSquare;

/**
 * @group  rule
 * @covers PerfectSquare
 * @covers PerfectSquareException
 */
class PerfectSquareTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new PerfectSquare();
    }

    /**
     * @dataProvider providerForPerfectSquare
     * @throws \Exception
     */
    public function testPerfectSquare($input): void
    {
        static::assertTrue($this->object->__invoke($input));
        static::assertTrue($this->object->check($input));
        static::assertTrue($this->object->assert($input));
    }

    /**
     * @dataProvider providerForNotPerfectSquare
     * @throws \Exception
     */
    public function testNotPerfectSquare($input): void
    {
        $this->expectException(PerfectSquareException::class);
        static::assertFalse($this->object->__invoke($input));
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForPerfectSquare(): array
    {
        return [
            [1],
            [9],
            [25],
            ['25'],
            [400],
            ['400'],
            ['0'],
            [81],
            [0],
            [2500],
        ];
    }

    public static function providerForNotPerfectSquare(): array
    {
        return [
            [250],
            [''],
            [null],
            [7],
            [-1],
            [6],
            [2],
            ['-1'],
            ['a'],
            [' '],
            ['Foo'],
        ];
    }
}
