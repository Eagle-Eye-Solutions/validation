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
use Respect\Validation\Exceptions\SpaceException;
use Respect\Validation\Rules\Space;

#[CoversClass(Space::class)]
#[CoversClass(SpaceException::class)]
#[Group('rule')]
class SpaceTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForValidSpace')]
    public function testValidDataWithSpaceShouldReturnTrue($validSpace, $additional = ''): void
    {
        $validator = new Space($additional);
        static::assertTrue($validator->validate($validSpace));
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForInvalidSpace')]
    public function testInvalidSpaceShouldFailAndThrowSpaceException($invalidSpace, $additional = ''): void
    {
        $this->expectException(SpaceException::class);
        $validator = new Space($additional);
        static::assertFalse($validator->validate($invalidSpace));
        static::assertFalse($validator->assert($invalidSpace));
    }

    #[DataProvider('providerForInvalidParams')]
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($additional)
    {
        $this->expectException(ComponentException::class);
        new Space($additional);
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerAdditionalChars')]
    public function testAdditionalCharsShouldBeRespected($additional, $query): void
    {
        $validator = new Space($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars(): array
    {
        return [
            ['!@#$%^&*(){}', '!@#$%^&*(){} '],
            ['[]?+=/\\-_|"\',<>.', "[]?+=/\\-_|\"',<>. \t \n "],
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

    public static function providerForValidSpace(): array
    {
        return [
            ["\n"],
            [' '],
            ['    '],
            ["\t"],
            ['	'],
        ];
    }

    public static function providerForInvalidSpace(): array
    {
        return [
            [''],
            ['16-50'],
            ['a'],
            ['Foo'],
            ['12.1'],
            ['-12'],
            [-12],
            ['_'],
        ];
    }
}
