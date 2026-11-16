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
use Respect\Validation\Exceptions\OddException;
use Respect\Validation\Rules\Odd;

#[CoversClass(Odd::class)]
#[CoversClass(OddException::class)]
#[Group('rule')]
class OddTest extends TestCase
{
    protected Odd $object;

    protected function setUp(): void
    {
        $this->object = new Odd();
    }

    /**
     * @throws \Exception
     */
    #[DataProvider('providerForOdd')]
    public function testOdd($input): void
    {
        static::assertTrue($this->object->assert($input));
        static::assertTrue($this->object->__invoke($input));
        static::assertTrue($this->object->check($input));
    }

    /**
     * @throws \Exception
     */
    #[DataProvider('providerForNotOdd')]
    public function testNotOdd($input): void
    {
        $this->expectException(OddException::class);
        static::assertFalse($this->object->__invoke($input));
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForOdd(): array
    {
        return [
            [-5],
            [-1],
            [1],
            [13],
        ];
    }

    public static function providerForNotOdd(): array
    {
        return [
            [''],
            [-2],
            [-0],
            [0],
            [32],
        ];
    }
}
