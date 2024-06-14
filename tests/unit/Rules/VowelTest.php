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
use Respect\Validation\Exceptions\VowelException;
use Respect\Validation\Rules\Vowel;

/**
 * @group  rule
 * @covers Vowel
 * @covers VowelException
 */
class VowelTest extends TestCase
{
    /**
     * @dataProvider providerForValidVowels
     * @throws ComponentException
     */
    public function testValidDataWithVowelsShouldReturnTrue($validVowels, $additional = ''): void
    {
        $validator = new Vowel($additional);
        static::assertTrue($validator->validate($validVowels));
    }

    /**
     * @dataProvider providerForInvalidVowels
     * @throws ComponentException
     */
    public function testInvalidVowelsShouldFailAndThrowVowelException($invalidVowels, $additional = ''): void
    {
        $this->expectException(VowelException::class);
        $validator = new Vowel($additional);
        static::assertFalse($validator->validate($invalidVowels));
        static::assertFalse($validator->assert($invalidVowels));
    }

    /**
     * @dataProvider providerForInvalidParams
     */
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($additional): void
    {
        $this->expectException(ComponentException::class);
        new Vowel($additional);
    }

    /**
     * @dataProvider providerAdditionalChars
     */
    public function testAdditionalCharsShouldBeRespected($additional, $query): void
    {
        $validator = new Vowel($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars(): array
    {
        return [
            ['!@#$%^&*(){}', '!@#$%^&*(){} aeo iu'],
            ['[]?+=/\\-_|"\',<>.', "[]?+=/\\-_|\"',<>. \t \n aeo iu"],
        ];
    }

    public static function providerForInvalidParams(): array
    {
        return [
            [new \stdClass()],
            [[]],
            [0x2],
        ];
    }

    public static function providerForValidVowels(): array
    {
        return [
            ['a'],
            ['e'],
            ['i'],
            ['o'],
            ['u'],
            ['aeiou'],
            ['aei ou'],
            ["\na\t"],
            ['uoiea'],
        ];
    }

    public static function providerForInvalidVowels(): array
    {
        return [
            [''],
            [null],
            ['16'],
            ['F'],
            ['g'],
            ['Foo'],
            [-50],
            ['basic'],
        ];
    }
}
