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
use Respect\Validation\Exceptions\BaseException;
use Respect\Validation\Rules\Base;

/**
 * @group  rule
 * @covers Base
 * @covers BaseException
 */
class BaseTest extends TestCase
{
    protected $object;

    /**
     * @dataProvider providerForBase
     * @throws \Exception
     */
    public function testBase($base, $input): void
    {
        $object = new Base($base);
        static::assertTrue($object->__invoke($input));
        static::assertTrue($object->check($input));
        static::assertTrue($object->assert($input));
    }

    /**
     * @dataProvider providerForInvalidBase
     */
    public function testInvalidBase($base, $input): void
    {
        $object = new Base($base);
        static::assertFalse($object->__invoke($input));
    }

    /**
     * @dataProvider providerForExceptionBase
     * @throws \Exception
     */
    public function testExceptionBase($base, $input): void
    {
        $this->expectException(BaseException::class);
        $object = new Base($base);
        static::assertTrue($object->__invoke($input));
        static::assertTrue($object->assert($input));
    }

    /**
     * @dataProvider providerForCustomBase
     * @throws \Exception
     */
    public function testCustomBase($base, $custom, $input): void
    {
        $object = new Base($base, $custom);
        static::assertTrue($object->__invoke($input));
        static::assertTrue($object->check($input));
        static::assertTrue($object->assert($input));
    }

    public static function providerForBase(): array
    {
        return [
            [2, '011010001'],
            [3, '0120122001'],
            [8, '01234567520'],
            [16, '012a34f5675c20d'],
            [20, '012ah34f5675hic20dj'],
            [50, '012ah34f56A75FGhic20dj'],
            [62, 'Z01xSsg5675hic20dj'],
        ];
    }

    public static function providerForInvalidBase(): array
    {
        return [
            [2, ''],
            [3, ''],
            [8, ''],
            [16, ''],
            [20, ''],
            [50, ''],
            [62, ''],
            [2, '01210103001'],
            [3, '0120125f2001'],
            [8, '01234dfZ567520'],
            [16, '012aXS34f5675c20d'],
            [20, '012ahZX34f5675hic20dj'],
            [50, '012ahGZ34f56A75FGhic20dj'],
            [61, 'Z01xSsg5675hic20dj'],
        ];
    }

    public static function providerForCustomBase(): array
    {
        return [
            [2, 'xy', 'xyyxyxxy'],
            [3, 'pfg', 'gfpffp'],
        ];
    }

    public static function providerForExceptionBase(): array
    {
        return [
            [63, '01210103001'],
            [125, '0120125f2001'],
        ];
    }
}
