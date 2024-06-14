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
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\PunctException;
use Respect\Validation\Rules\Punct;

/**
 * @group  rule
 * @covers Punct
 * @covers PunctException
 */
class PunctTest extends TestCase
{
    /**
     * @dataProvider providerForValidPunct
     */
    public function testValidDataWithPunctShouldReturnTrue($validPunct, $additional = '')
    {
        $validator = new Punct($additional);
        static::assertTrue($validator->validate($validPunct));
    }

    /**
     * @dataProvider providerForInvalidPunct
     *
     */
    public function testInvalidPunctShouldFailAndThrowPunctException($invalidPunct, $additional = '')
    {
        $this->expectException(PunctException::class);
        $validator = new Punct($additional);
        static::assertFalse($validator->validate($invalidPunct));
        static::assertFalse($validator->assert($invalidPunct));
    }

    /**
     * @dataProvider providerForInvalidParams
     *
     */
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($additional)
    {
        $this->expectException(ComponentException::class);
        new Punct($additional);
    }

    /**
     * @dataProvider providerAdditionalChars
     */
    public function testAdditionalCharsShouldBeRespected($additional, $query)
    {
        $validator = new Punct($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars()
    {
        return [
            ['abc123 ', '!@#$%^&*(){} abc 123'],
            ["abc123 \t\n", "[]?+=/\\-_|\"',<>. \t \n abc 123"],
        ];
    }

    public static function providerForInvalidParams()
    {
        return [
            [new \stdClass()],
            [[]],
            [0x2],
        ];
    }

    public static function providerForValidPunct()
    {
        return [
            ['.'],
            [',;:'],
            ['-@#$*'],
            ['()[]{}'],
        ];
    }

    public static function providerForInvalidPunct()
    {
        return [
            [''],
            ['16-50'],
            ['a'],
            [' '],
            ['Foo'],
            ['12.1'],
            ['-12'],
            [-12],
            ['( )_{}'],
        ];
    }
}
