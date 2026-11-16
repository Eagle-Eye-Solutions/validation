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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\AlphaException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Alpha;

#[CoversClass(Alpha::class)]
#[CoversClass(AlphaException::class)]
#[Group('rule')]
class AlphaTest extends TestCase
{
    #[DataProvider('providerForValidAlpha')]
    public function testValidAlphanumericCharsShouldReturnTrue($validAlpha, $additional)
    {
        $validator = new Alpha($additional);
        static::assertTrue($validator->validate($validAlpha));
        static::assertTrue($validator->check($validAlpha));
        static::assertTrue($validator->assert($validAlpha));
    }

    #[DataProvider('providerForInvalidAlpha')]
    public function testInvalidAlphanumericCharsShouldThrowAlphaException($invalidAlpha, $additional)
    {
        $this->expectException(AlphaException::class);
        $validator = new Alpha($additional);
        static::assertFalse($validator->validate($invalidAlpha));
        static::assertFalse($validator->assert($invalidAlpha));
    }

    #[DataProvider('providerForInvalidParams')]
    public function testInvalidConstructorParamsShouldThrowComponentException($additional)
    {
        $this->expectException(ComponentException::class);
         new Alpha($additional);
    }

    #[DataProvider('providerAdditionalChars')]
    public function testAdditionalCharsShouldBeRespected($additional, $query)
    {
        $validator = new Alpha($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars()
    {
        return [
            ['!@#$%^&*(){}', '!@#$%^&*(){} abc'],
            ['[]?+=/\\-_|"\',<>.', "[]?+=/\\-_|\"',<>. \t \n abc"],
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

    public static function providerForValidAlpha()
    {
        return [
            ['alganet', ''],
            ['alganet', 'alganet'],
            ['0alg-anet0', '0-9'],
            ['a', ''],
            ["\t", ''],
            ["\n", ''],
            ['foobar', ''],
            ['rubinho_', '_'],
            ['google.com', '.'],
            ['alganet alganet', ''],
            ["\nabc", ''],
            ["\tdef", ''],
            ["\nabc \t", ''],
        ];
    }

    public static function providerForInvalidAlpha()
    {
        return [
            ['', ''],
            ['@#$', ''],
            ['_', ''],
            ['dgç', ''],
            ['122al', ''],
            ['122', ''],
            [11123, ''],
            [1e21, ''],
            [0, ''],
            [null, ''],
            [new \stdClass(), ''],
            [[], ''],
        ];
    }
}
