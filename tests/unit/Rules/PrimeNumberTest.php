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
use Respect\Validation\Exceptions\PrimeNumberException;
use Respect\Validation\Rules\PrimeNumber;

#[CoversClass(PrimeNumber::class)]
#[CoversClass(PrimeNumberException::class)]
#[Group('rule')]
class PrimeNumberTest extends TestCase
{
    protected PrimeNumber $object;

    protected function setUp(): void
    {
        $this->object = new PrimeNumber();
    }

    /**
     * @throws \Exception
     */
    #[DataProvider('providerForPrimeNumber')]
    public function testPrimeNumber($input): void
    {
        static::assertTrue($this->object->__invoke($input));
        static::assertTrue($this->object->check($input));
        static::assertTrue($this->object->assert($input));
    }

    /**
     * @throws \Exception
     */
    #[DataProvider('providerForNotPrimeNumber')]
    public function testNotPrimeNumber($input): void
    {
        $this->expectException(PrimeNumberException::class);
        static::assertFalse($this->object->__invoke($input));
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForPrimeNumber(): array
    {
        return [
            [3],
            [5],
            [7],
            ['3'],
            ['5'],
            ['+7'],
        ];
    }

    public static function providerForNotPrimeNumber(): array
    {
        return [
            [''],
            [null],
            [0],
            [10],
            [25],
            [36],
            [-1],
            ['-1'],
            ['25'],
            ['0'],
            ['a'],
            [' '],
            ['Foo'],
        ];
    }
}
