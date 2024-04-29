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
use Respect\Validation\Exceptions\NullTypeException;
use Respect\Validation\Rules\NullType;

/**
 * @group  rule
 * @covers NullType
 * @covers NullTypeException
 */
class NullTypeTest extends TestCase
{
    protected NullType $object;

    protected function setUp(): void
    {
        $this->object = new NullType();
    }

    /**
     * @throws \Exception
     */
    public function testNullValue(): void
    {
        static::assertTrue($this->object->assert(null));
        static::assertTrue($this->object->__invoke(null));
        static::assertTrue($this->object->check(null));
    }

    /**
     * @dataProvider providerForNotNull
     *
     */
    public function testNotNull($input): void
    {
        $this->expectException(NullTypeException::class);
        static::assertFalse($this->object->__invoke($input));
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForNotNull(): array
    {
        return [
            [''],
            [0],
            ['w poiur'],
            [' '],
            ['Foo'],
        ];
    }
}
