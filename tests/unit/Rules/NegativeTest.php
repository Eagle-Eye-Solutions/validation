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
use Respect\Validation\Exceptions\NegativeException;
use Respect\Validation\Rules\Negative;

/**
 * @group  rule
 * @covers Negative
 * @covers NegativeException
 */
class NegativeTest extends TestCase
{
    protected Negative $negativeValidator;

    protected function setUp(): void
    {
        $this->negativeValidator = new Negative();
    }

    /**
     * @dataProvider providerForNegative
     */
    public function testNegativeShouldPass($input): void
    {
        static::assertTrue($this->negativeValidator->assert($input));
        static::assertTrue($this->negativeValidator->__invoke($input));
        static::assertTrue($this->negativeValidator->check($input));
    }

    /**
     * @dataProvider providerForNotNegative
     * @throws \Exception
     */
    public function testNotNegativeNumbers($input): void
    {
        $this->expectException(NegativeException::class);
        static::assertFalse($this->negativeValidator->__invoke($input));
        static::assertFalse($this->negativeValidator->assert($input));
    }

    public static function providerForNegative(): array
    {
        return [
            ['-1.44'],
            [-1e-5],
            [-10],
            [''],
            [' '],
        ];
    }

    public static function providerForNotNegative(): array
    {
        return [
            [0],
            [-0],
            [null],
            ['a'],
            ['Foo'],
            [16],
            ['165'],
            [123456],
            [1e10],
        ];
    }
}
