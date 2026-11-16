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
use Respect\Validation\Exceptions\FloatValException;
use Respect\Validation\Rules\FloatVal;

#[CoversClass(FloatVal::class)]
#[CoversClass(FloatValException::class)]
#[Group('rule')]
class FloatValTest extends TestCase
{
    protected $floatValidator;

    protected function setUp(): void
    {
        $this->floatValidator = new FloatVal();
    }

    #[DataProvider('providerForFloat')]
    public function testFloatNumbersShouldPass($input): void
    {
        static::assertTrue($this->floatValidator->assert($input));
        static::assertTrue($this->floatValidator->__invoke($input));
        static::assertTrue($this->floatValidator->check($input));
    }

    #[DataProvider('providerForNotFloat')]
    public function testNotFloatNumbersShouldFail($input): void
    {
        $this->expectException(FloatValException::class);
        static::assertFalse($this->floatValidator->__invoke($input));
        static::assertFalse($this->floatValidator->assert($input));
    }

    public static function providerForFloat(): array
    {
        return [
            [165],
            [1],
            [0],
            [0.0],
            ['1'],
            ['19347e12'],
            [165.0],
            ['165.7'],
            [1e12],
        ];
    }

    public static function providerForNotFloat(): array
    {
        return [
            [''],
            [null],
            ['a'],
            [' '],
            ['Foo'],
        ];
    }
}
