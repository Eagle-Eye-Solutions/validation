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
use Respect\Validation\Exceptions\GraphException;
use Respect\Validation\Rules\Graph;

#[CoversClass(Graph::class)]
#[CoversClass(GraphException::class)]
#[Group('rule')]
class GraphTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForValidGraph')]
    public function testValidDataWithGraphCharsShouldReturnTrue($validGraph, $additional = ''): void
    {
        $validator = new Graph($additional);
        static::assertTrue($validator->validate($validGraph));
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForInvalidGraph')]
    public function testInvalidGraphShouldFailAndThrowGraphException($invalidGraph, $additional = ''): void
    {
        $this->expectException(GraphException::class);
        $validator = new Graph($additional);
        static::assertFalse($validator->validate($invalidGraph));
        static::assertFalse($validator->assert($invalidGraph));
    }

    #[DataProvider('providerForInvalidParams')]
    public function testInvalidConstructorParams($additional): void
    {
        $this->expectException(ComponentException::class);
        new Graph($additional);
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerAdditionalChars')]
    public function testAdditionalCharsShouldBeRespected($additional, $query): void
    {
        $validator = new Graph($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars(): array
    {
        return [
            [' ', '!@#$%^&*(){} abc 123'],
            [" \t\n", "[]?+=/\\-_|\"',<>. \t \n abc 123"],
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

    public static function providerForValidGraph()
    {
        return [
            ['LKA#@%.54'],
            ['foobar'],
            ['16-50'],
            ['123'],
            ['#$%&*_'],
        ];
    }

    public static function providerForInvalidGraph()
    {
        return [
            [''],
            [null],
            ["foo\nbar"],
            ["foo\tbar"],
            ['foo bar'],
            [' '],
        ];
    }
}
