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
use Respect\Validation\Exceptions\StringTypeException;
use Respect\Validation\Rules\StringType;

#[CoversClass(StringType::class)]
#[CoversClass(StringTypeException::class)]
#[Group('rule')]
class StringTypeTest extends TestCase
{
    #[DataProvider('providerForString')]
    public function testString($input): void
    {
        $rule = new StringType();

        static::assertTrue($rule->validate($input));
    }

    #[DataProvider('providerForNotString')]
    public function testNotString($input): void
    {
        $rule = new StringType();

        static::assertFalse($rule->validate($input));
    }

    public static function providerForString(): array
    {
        return [
            [''],
            ['165.7'],
        ];
    }

    public static function providerForNotString(): array
    {
        return [
            [null],
            [[]],
            [new \stdClass()],
            [150],
        ];
    }
}
