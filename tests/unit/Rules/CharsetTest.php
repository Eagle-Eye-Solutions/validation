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
use Respect\Validation\Exceptions\CharsetException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Charset;

/**
 * @group  rule
 * @covers Charset
 * @covers CharsetException
 */
class CharsetTest extends TestCase
{
    /**
     * @dataProvider providerForValidCharset
     * @throws ComponentException
     */
    public function testValidDataWithCharsetShouldReturnTrue($charset, $input)
    {
        $validator = new Charset($charset);
        static::assertTrue($validator->__invoke($input));
    }

    /**
     * @dataProvider providerForInvalidCharset
     * @throws ComponentException
     */
    public function testInvalidCharsetShouldFailAndThrowCharsetException($charset, $input)
    {
        $this->expectException(CharsetException::class);
        $validator = new Charset($charset);
        static::assertFalse($validator->__invoke($input));
        static::assertFalse($validator->assert($input));
    }

    /**
     * @dataProvider providerForInvalidParams
     *
     */
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($charset)
    {
        $this->expectException(ComponentException::class);
        new Charset($charset);
    }

    public static function providerForInvalidParams()
    {
        return [
            [new \stdClass()],
            [[]],
            [null],
            ['16'],
            ['aeiou'],
            ['a'],
            ['Foo'],
            ['basic'],
            [10],
        ];
    }

    public static function providerForValidCharset()
    {
        return [
            ['UTF-8', ''],
            ['ISO-8859-1', mb_convert_encoding('açaí', 'ISO-8859-1')],
            [['UTF-8', 'ASCII'], 'strawberry'],
            ['ASCII', mb_convert_encoding('strawberry', 'ASCII')],
            ['UTF-8', '日本国'],
            [['ISO-8859-1', 'EUC-JP'], '日本国'],
            ['UTF-8', 'açaí'],
            ['ISO-8859-1', 'açaí'],
        ];
    }

    public static function providerForInvalidCharset()
    {
        return [
            ['ASCII', '日本国'],
            ['ASCII', 'açaí'],
        ];
    }
}
