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
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\VowelException;
use Respect\Validation\Rules\Vowel;

#[CoversClass(Vowel::class)]
#[CoversClass(VowelException::class)]
#[Group('rule')]
class VowelTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForValidVowels')]
    public function testValidDataWithVowelsShouldReturnTrue($validVowels, $additional = ''): void
    {
        $validator = new Vowel($additional);
        static::assertTrue($validator->validate($validVowels));
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForInvalidVowels')]
    public function testInvalidVowelsShouldFailAndThrowVowelException($invalidVowels, $additional = ''): void
    {
        $this->expectException(VowelException::class);
        $validator = new Vowel($additional);
        static::assertFalse($validator->validate($invalidVowels));
        static::assertFalse($validator->assert($invalidVowels));
    }

    #[DataProvider('providerForInvalidParams')]
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($additional): void
    {
        $this->expectException(ComponentException::class);
        new Vowel($additional);
    }

    #[DataProvider('providerAdditionalChars')]
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
