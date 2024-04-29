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
use Respect\Validation\Exceptions\FiniteException;
use Respect\Validation\Rules\Finite;

/**
 * @group  rule
 * @covers Finite
 * @covers FiniteException
 */
class FiniteTest extends TestCase
{
    protected $rule;

    protected function setUp(): void
    {
        $this->rule = new Finite();
    }

    /**
     * @dataProvider providerForFinite
     */
    public function testShouldValidateFiniteNumbers($input): void
    {
        static::assertTrue($this->rule->validate($input));
    }

    /**
     * @dataProvider providerForNonFinite
     */
    public function testShouldNotValidateNonFiniteNumbers($input)
    {
        static::assertFalse($this->rule->validate($input));
    }

    public function testShouldThrowFiniteExceptionWhenChecking(): void
    {
        $this->expectExceptionMessage("INF must be a finite number");
        $this->expectException(FiniteException::class);
        $this->rule->check(INF);
    }

    public static function providerForFinite(): array
    {
        return [
            ['123456'],
            [-9],
            [0],
            [16],
            [2],
            [PHP_INT_MAX],
        ];
    }

    public static function providerForNonFinite(): array
    {
        return [
            [' '],
            [INF],
            [[]],
            [new \stdClass()],
            [null],
        ];
    }
}
