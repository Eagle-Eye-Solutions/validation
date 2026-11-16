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
use Respect\Validation\Exceptions\ScalarValException;
use Respect\Validation\Rules\ScalarVal;

#[CoversClass(ScalarVal::class)]
#[CoversClass(ScalarValException::class)]
#[Group('rule')]
class ScalarValTest extends TestCase
{
    protected $rule;

    protected function setUp(): void
    {
        $this->rule = new ScalarVal();
    }

    #[DataProvider('providerForScalar')]
    public function testShouldValidateScalarNumbers($input): void
    {
        static::assertTrue($this->rule->validate($input));
    }

    #[DataProvider('providerForNonScalar')]
    public function testShouldNotValidateNonScalarNumbers($input): void
    {
        static::assertFalse($this->rule->validate($input));
    }

    public function testShouldThrowScalarExceptionWhenChecking(): void
    {
        $this->expectExceptionMessage("null must be a scalar value");
        $this->expectException(ScalarValException::class);
        $this->rule->check(null);
    }

    public static function providerForScalar(): array
    {
        return [
            ['6'],
            ['String'],
            [1.0],
            [42],
            [false],
            [true],
        ];
    }

    public static function providerForNonScalar()
    {
        return [
            [[]],
            [function (): void {
            }],
            [new \stdClass()],
            [null],
            [tmpfile()],
        ];
    }
}
