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
use Respect\Validation\Exceptions\EqualsException;
use Respect\Validation\Rules\Equals;
use stdClass;

#[CoversClass(Equals::class)]
#[CoversClass(EqualsException::class)]
#[Group('rule')]
class EqualsTest extends TestCase
{
    #[DataProvider('providerForEquals')]
    public function testInputEqualsToExpectedValueShouldPass($compareTo, $input): void
    {
        $rule = new Equals($compareTo);

        static::assertTrue($rule->validate($input));
    }

    #[DataProvider('providerForNotEquals')]
    public function testInputNotEqualsToExpectedValueShouldPass($compareTo, $input): void
    {
        $rule = new Equals($compareTo);

        static::assertFalse($rule->validate($input));
    }

    public function testShouldThrowTheProperExceptionWhenFailure(): void
    {
        $this->expectExceptionMessage("\"24\" must be equals 42");
        $this->expectException(EqualsException::class);
        $rule = new Equals(42);
        $rule->check('24');
    }

    public static function providerForEquals(): array
    {
        return [
            ['foo', 'foo'],
            [[], []],
            [new stdClass(), new stdClass()],
            [10, '10'],
        ];
    }

    public static function providerForNotEquals(): array
    {
        return [
            ['foo', ''],
            ['foo', 'bar'],
        ];
    }
}
