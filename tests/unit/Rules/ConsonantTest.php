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
use Respect\Validation\Exceptions\ConsonantException;
use Respect\Validation\Rules\Consonant;

#[CoversClass(Consonant::class)]
#[CoversClass(ConsonantException::class)]
#[Group('rule')]
class ConsonantTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForValidConsonants')]
    public function testValidDataWithConsonants($validConsonants, $additional = ''): void
    {
        $validator = new Consonant($additional);
        static::assertTrue($validator->validate($validConsonants));
    }

    /**
     * @throws ComponentException
     * @throws \Exception
     */
    #[DataProvider('providerForInvalidConsonants')]
    public function testInvalidConsonants($invalidConsonants, $additional = '')
    {
        $this->expectException(ConsonantException::class);
        $validator = new Consonant($additional);
        static::assertFalse($validator->validate($invalidConsonants));
        static::assertFalse($validator->assert($invalidConsonants));
    }

    #[DataProvider('providerForInvalidParams')]
    public function testInvalidConstructorParams($additional): void
    {
        $this->expectException(ComponentException::class);
        new Consonant($additional);
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerAdditionalChars')]
    public function testAdditionalCharsd($additional, $query)
    {
        $validator = new Consonant($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars(): array
    {
        return [
            ['!@#$%^&*(){}', '!@#$%^&*(){} bc dfg'],
            ['[]?+=/\\-_|"\',<>.', "[]?+=/\\-_|\"',<>. \t \n bc dfg"],
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

    public static function providerForValidConsonants(): array
    {
        return [
            ['b'],
            ['c'],
            ['d'],
            ['w'],
            ['y'],
            ['y', ''],
            ['bcdfghklmnp'],
            ['bcdfghklm np'],
            ['qrst'],
            ["\nz\t"],
            ['zbcxwyrspq'],
        ];
    }

    public static function providerForInvalidConsonants(): array
    {
        return [
            [''],
            [null],
            ['16'],
            ['aeiou'],
            ['a'],
            ['Foo'],
            [-50],
            ['basic'],
        ];
    }
}
