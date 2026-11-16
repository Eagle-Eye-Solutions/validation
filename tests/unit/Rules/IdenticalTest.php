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
use Respect\Validation\Exceptions\IdenticalException;
use Respect\Validation\Rules\Identical;
use stdClass;

#[CoversClass(Identical::class)]
#[CoversClass(IdenticalException::class)]
#[Group('rule')]
class IdenticalTest extends TestCase
{
    #[DataProvider('providerForIdentical')]
    public function testInputIdenticalToExpectedValueShouldPass($compareTo, $input): void
    {
        $rule = new Identical($compareTo);

        static::assertTrue($rule->validate($input));
    }

    #[DataProvider('providerForNotIdentical')]
    public function testInputNotIdenticalToExpectedValueShouldPass($compareTo, $input): void
    {
        $rule = new Identical($compareTo);

        static::assertFalse($rule->validate($input));
    }

    public function testShouldThrowTheProperExceptionWhenFailure(): void
    {
        $this->expectException(IdenticalException::class);
        $this->expectExceptionMessage("\"42\" must be identical as 42");
        $rule = new Identical(42);
        $rule->check('42');
    }

    public static function providerForIdentical(): array
    {
        $object = new stdClass();

        return [
            ['foo', 'foo'],
            [[], []],
            [$object, $object],
            [10, 10],
        ];
    }

    public static function providerForNotIdentical(): array
    {
        return [
            [42, '42'],
            ['foo', 'bar'],
            [[1], []],
            [new stdClass(), new stdClass()],
        ];
    }
}
