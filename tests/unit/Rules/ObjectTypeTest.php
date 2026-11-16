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
use Respect\Validation\Exceptions\ObjectTypeException;
use Respect\Validation\Rules\ObjectType;

#[CoversClass(ObjectType::class)]
#[CoversClass(ObjectTypeException::class)]
#[Group('rule')]
class ObjectTypeTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new ObjectType();
    }

    /**
     * @throws \Exception
     */
    #[DataProvider('providerForObject')]
    public function testObject($input): void
    {
        static::assertTrue($this->object->__invoke($input));
        static::assertTrue($this->object->assert($input));
        static::assertTrue($this->object->check($input));
    }

    #[DataProvider('providerForNotObject')]
    public function testNotObject($input): void
    {
        $this->expectException(ObjectTypeException::class);
        static::assertFalse($this->object->__invoke($input));
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForObject(): array
    {
        return [
            [new \stdClass()],
            [new \ArrayObject()],
        ];
    }

    public static function providerForNotObject(): array
    {
        return [
            [''],
            [null],
            [121],
            [[]],
            ['Foo'],
            [false],
        ];
    }
}
