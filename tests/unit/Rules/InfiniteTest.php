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
use Respect\Validation\Exceptions\InfiniteException;
use Respect\Validation\Rules\Infinite;

/**
 * @group  rule
 * @covers Infinite
 * @covers InfiniteException
 */
class InfiniteTest extends TestCase
{
    protected $rule;

    protected function setUp(): void
    {
        $this->rule = new Infinite();
    }

    /**
     * @dataProvider providerForInfinite
     */
    public function testShouldValidateInfiniteNumbers($input): void
    {
        static::assertTrue($this->rule->validate($input));
    }

    /**
     * @dataProvider providerForNonInfinite
     */
    public function testShouldNotValidateNonInfiniteNumbers($input): void
    {
        static::assertFalse($this->rule->validate($input));
    }

    public function testShouldThrowInfiniteExceptionWhenChecking(): void
    {
        $this->expectException(InfiniteException::class);
        $this->expectExceptionMessage("123456 must be an infinite number");
        $this->rule->check(123456);
    }

    public static function providerForInfinite(): array
    {
        return [
            [INF],
            [INF * -1],
        ];
    }

    public static function providerForNonInfinite(): array
    {
        return [
            [' '],
            [[]],
            [new \stdClass()],
            [null],
            ['123456'],
            [-9],
            [0],
            [16],
            [2],
            [PHP_INT_MAX],
        ];
    }
}
