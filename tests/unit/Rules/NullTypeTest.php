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
use Respect\Validation\Exceptions\NullTypeException;
use Respect\Validation\Rules\NullType;

#[CoversClass(NullType::class)]
#[CoversClass(NullTypeException::class)]
#[Group('rule')]
class NullTypeTest extends TestCase
{
    protected NullType $object;

    protected function setUp(): void
    {
        $this->object = new NullType();
    }

    /**
     * @throws \Exception
     */
    public function testNullValue(): void
    {
        static::assertTrue($this->object->assert(null));
        static::assertTrue($this->object->__invoke(null));
        static::assertTrue($this->object->check(null));
    }

    #[DataProvider('providerForNotNull')]
    public function testNotNull($input): void
    {
        $this->expectException(NullTypeException::class);
        static::assertFalse($this->object->__invoke($input));
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForNotNull(): array
    {
        return [
            [''],
            [0],
            ['w poiur'],
            [' '],
            ['Foo'],
        ];
    }
}
