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
use Respect\Validation\Exceptions\NumericValException;
use Respect\Validation\Rules\NumericVal;

/**
 * @group  rule
 * @covers NumericVal
 * @covers NumericValException
 */
class NumericValTest extends TestCase
{
    protected NumericVal $object;

    protected function setUp(): void
    {
        $this->object = new NumericVal();
    }

    /**
     * @dataProvider providerForNumeric
     * @throws \Exception
     */
    public function testNumeric($input): void
    {
        static::assertTrue($this->object->__invoke($input));
        static::assertTrue($this->object->check($input));
        static::assertTrue($this->object->assert($input));
    }

    /**
     * @dataProvider providerForNotNumeric
     * @throws \Exception
     */
    public function testNotNumeric($input): void
    {
        $this->expectException(NumericValException::class);
        static::assertFalse($this->object->__invoke($input));
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForNumeric(): array
    {
        return [
            [165],
            [165.0],
            [-165],
            ['165'],
            ['165.0'],
            ['+165.0'],
        ];
    }

    public static function providerForNotNumeric(): array
    {
        return [
            [''],
            [null],
            ['a'],
            [' '],
            ['Foo'],
        ];
    }
}
