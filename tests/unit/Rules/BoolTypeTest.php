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
use Respect\Validation\Exceptions\BoolTypeException;
use Respect\Validation\Rules\BoolType;

/**
 * @group  rule
 * @covers BoolType
 * @covers BoolTypeException
 */
class BoolTypeTest extends TestCase
{
    public function testBooleanValuesONLYShouldReturnTrue()
    {
        $validator = new BoolType();
        static::assertTrue($validator->__invoke(true));
        static::assertTrue($validator->__invoke(false));
        static::assertTrue($validator->assert(true));
        static::assertTrue($validator->assert(false));
        static::assertTrue($validator->check(true));
        static::assertTrue($validator->check(false));
    }

    public function testInvalidBooleanShouldRaiseException()
    {
        $this->expectException(BoolTypeException::class);
        $validator = new BoolType();
        static::assertFalse($validator->check('foo'));
    }

    public function testInvalidBooleanValuesShouldReturnFalse()
    {
        $validator = new BoolType();
        static::assertFalse($validator->__invoke(''));
        static::assertFalse($validator->__invoke('foo'));
        static::assertFalse($validator->__invoke(123123));
        static::assertFalse($validator->__invoke(new \stdClass()));
        static::assertFalse($validator->__invoke([]));
        static::assertFalse($validator->__invoke(1));
        static::assertFalse($validator->__invoke(0));
        static::assertFalse($validator->__invoke(null));
    }
}
